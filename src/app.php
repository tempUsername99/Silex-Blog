<?php

//ServiceProvider Registrator File

use Silex\Application;
use Silex\Provider\AssetServiceProvider;
use Silex\Provider\CsrfServiceProvider;
use Silex\Provider\SecurityServiceProvider;
use Silex\Provider\SessionServiceProvider;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\ServiceControllerServiceProvider;
use Silex\Provider\HttpFragmentServiceProvider;
use Silex\Provider\FormServiceProvider;

$app = new Application();

$app->register(new ServiceControllerServiceProvider());
$app->register(new AssetServiceProvider());
$app->register(new TwigServiceProvider());
$app->register(new HttpFragmentServiceProvider());
$app->register(new CsrfServiceProvider());
$app->register(new FormServiceProvider());
$app->register(new SessionServiceProvider());
$app->register(new SecurityServiceProvider());
$app->register(new SecurityServiceProvider(), [
    'security.firewalls' => array(
        'main' => array(
            'pattern' => '^.*$',
            'anonymous' => true,
            'form' => array(
                'login_path' => '/login',
                'check_path' => '/admin/auth',
            ),
            'logout' => array('logout_path' => '/logout'),
            'users' => new UserProvider($dm)
        )
    ),
    'security.access_rules' => array(
        array('^/admin', 'ROLE_USER'),
        array('^.*$', 'IS_AUTHENTICATED_ANONYMOUSLY')
    )
]);


return $app;
