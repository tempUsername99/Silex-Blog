<?php

//мне не удалось вынести конкретные функции или целый контроллер, т.к. выдавало 500 ошибку

use Documents\Post;
use Documents\Category;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

//region Public

    //region Web

$app->get('/', function() use($app, $dm) {
    $posts = $dm->createQueryBuilder('Documents\Post')
                ->sort('views', 'desc')
                ->limit(5)
                ->getQuery()
                ->execute();

    return $app['twig']->load('public/web/index.html.twig')->render(array(
        'posts' => $posts,
    ));
});

$app->get('/login', function(Application $app, Request $request){
    return $app['twig']->load('public/web/adminLogin.html.twig')->render([
        'error'         => $app['security.last_error']($request),
        'last_username' => $app['session']->get('_security.last_username'),
    ]);
});

$app->get('/categories', function () use ($app, $dm) {
    $categories = $dm->createQueryBuilder('Documents\Category')
        ->sort('name')
        ->getQuery()
        ->execute();

    return $app['twig']->load('public/web/categories.html.twig')->render(array(
        'categories' => $categories,
    ));
});

$app->get('/category/{id}', function ($id) use ($app, $dm) {
    $temp = $dm->getRepository('Documents\Category')->findOneBy(array('id' => $id));

    $posts = $temp->getPosts();

    return $app['twig']->load('public/web/category.html.twig')->render(array(
        'posts' => $posts,
    ));
});

//сделано, чтобы хоть где-то использовать ajax
$app->get('/articles/{id}', function ($id) use ($dm) {
    $post = $dm->getRepository('Documents\Post')->findOneBy(array('id' => $id));

    $post->incrementViews();

    $dm->merge($post);

    $dm->flush();

    return $post->getViews();
});

$app->post('/search', function (Request $request) use ($app, $dm) {
    $regex = new \MongoRegex('/.*' . $request->get('keyword') . '.*/i');

    $qb = $dm->getRepository('Documents\Post')->createQueryBuilder('o');

    $posts = $qb->addOr($qb->expr()->field('title')->equals($regex))//поиск по названия статьи
                ->addOr($qb->expr()->field('description')->equals($regex))//поиск по описанию
                ->addOr($qb->expr()->field('body')->equals($regex))//поиск по телу статьи
    ->getQuery()->execute();

    return $app['twig']->load('public/web/search.html.twig')->render(array(
        'posts' => $posts,
    ));
});

$app->get('/api', function() use($app)
{
    return $app['twig']->load('public/api/api.html.twig')->render();
});

    //endregion Web

    //region Api

$app->get('/api.getCategories', function () use ($app, $dm) {
    $categoriesResponse = $dm->createQueryBuilder('Documents\Category')
                             ->sort('name')
                             ->getQuery()
                             ->execute();
    $categories = [];

    foreach ($categoriesResponse as $category) {
        array_push($categories, [$category->getName() => $category->getId()]);
    }

    return json_encode($categories);
});

$app->get('/api.getPosts/{id}', function ($id) use ($app, $dm) {
    $postsResponse = $dm->getRepository('Documents\Category')->findOneBy(array('id' => $id))->getPosts();

    $posts = [];

    foreach ($postsResponse as $post)
    {
        array_push($posts, $post->getAllData());
    }

    return json_encode($posts, JSON_UNESCAPED_UNICODE);

});

    //endregion Api

//endregion Public

//region Admin

$app->get('/admin', function() use($app) {
    return $app['twig']->load('admin/admin.html.twig')->render();
});

    //region categories CRUD (CREATE, READ, UPDATE, DELETE)

$app->get('/admin/categories', function() use($app, $dm) {
    $categories = $dm->createQueryBuilder('Documents\Category')
        ->sort('name')
        ->getQuery()
        ->execute();
    return $app['twig']->load('admin/categories.html.twig')->render(['categories' => $categories]);
});

$app->get('/admin/categories/create', function() use ($app, $dm) {
    return $app['twig']->load('admin/createCategory.html.twig')->render(['name' => '']);
});

$app->post('/admin/categories/create', function(Request $request) use ($app, $dm) {
    $category = new Category();
    $category->setName($request->get('name'));
    $dm->persist($category);
    $dm->flush();
    return $app->redirect('/admin/categories');
});

$app->get('/admin/categories/{id}/edit', function($id) use ($app, $dm) {
    $temp = $dm->getRepository('Documents\Category')->findOneBy(array('id' => $id));
    return $app['twig']->load('admin/editCategory.html.twig')->render(['name' => $temp->getName(), 'id' => $id]);
});

$app->post('/admin/categories/{id}/edit', function(Request $request, $id) use ($app, $dm) {
    $temp = $dm->getRepository('Documents\Category')->findOneBy(array('id' => $id));
    $temp->setName($request->get('name'));
    $dm->merge($temp);
    $dm->flush();
    return $app->redirect('/admin/categories');
});

$app->post('/admin/categories/{id}/delete', function(Request $request, $id) use($app, $dm) {
    $temp = $dm->getRepository('Documents\Category')->findOneBy(array('id' => $id));
    //region cascade delete
    //сделано так, потому что не смог найти нужное св-во (в докумение) для автоматического удаления
    $posts = $temp->getPosts();
    foreach ($posts as $post) $dm->remove($post);
    //endregion cascade delete
    $dm->remove($temp);
    $dm->flush();
    return $app->redirect($request->headers->get('referer'));
});

    //endregion categories CRUD

    //region posts CRUD

$app->get('/admin/posts', function() use($app, $dm) {
    $posts = $dm->createQueryBuilder('Documents\Post')
        ->sort('title')
        ->getQuery()
        ->execute();
    return $app['twig']->load('admin/posts.html.twig')->render(['posts' => $posts]);
});

$app->get('/admin/posts/create', function() use ($app, $dm) {
    //сделано чтобы twig не выдавал исключений (null)
    $category = new Category();
    $category->setId('000000');
    $post = new Post();
    $post->setCategory($category);
    //
    $categories = $dm->getRepository('Documents\Category')->findAll();
    return $app['twig']->load('admin/createPost.html.twig')->render(['post' => $post, 'categories' => $categories]);
});

$app->post('/admin/posts/create', function(Request $request) use ($app, $dm) {
    $category = $dm->getRepository('Documents\Category')
        ->findOneBy(array('name' => $request->get('category')));

    $post = new Post();

    //region initialization
    $post->setTitle($request->get('title'));
    $post->setDescription($request->get('description'));
    $post->setImage($request->get('image'));
    $post->setBody($request->get('body'));
    $post->setCategory($category);
    $post->setViews(0);
    $post->setDate(new \DateTime());
    //endregion

    $dm->persist($post);

    $dm->flush();

    return $app->redirect('/admin/posts');
});

$app->get('/admin/posts/{id}/edit', function($id) use ($app, $dm) {
    $temp = $dm->getRepository('Documents\Post')->findOneBy(array('id' => $id));
    //это для того что была возможность редактирования категории
    $categories = $dm->getRepository('Documents\Category')->findAll();
    return $app['twig']->load('admin/editPost.html.twig')->render(['post' => $temp, 'categories' => $categories]);
});

$app->post('/admin/posts/{id}/edit', function(Request $request, $id) use ($app, $dm) {
    //находим нужную категорию
    $category = $dm->getRepository('Documents\Category')
        ->findOneBy(array('name' => $request->get('category')));

    $temp = $dm->getRepository('Documents\Post')->findOneBy(array('id' => $id));

    $temp->setTitle($request->get('title'));
    $temp->setDescription($request->get('description'));
    $temp->setImage($request->get('image'));
    $temp->setBody($request->get('body'));
    //устанавливаем категорию
    $temp->setCategory($category);
    $dm->merge($temp);
    $dm->flush();

    return $app->redirect('/admin/posts');
});

$app->post('/admin/posts/{id}/delete', function(Request $request, $id) use($app, $dm) {
    $post = $dm->getRepository('Documents\Post')->findOneBy(array('id' => $id));
    $dm->remove($post);
    $dm->flush();
    return $app->redirect('/admin/posts');
});

    //endregion posts CRUD


//endregion Admin
