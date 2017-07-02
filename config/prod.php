<?php

// configure your app for the production environment

$app['twig'] = $app->extend('twig', function ($twig) {
    return $twig;
});
$app['twig.path'] = array(__DIR__.'/../templates');
$app['twig.options'] = array('cache' => __DIR__.'/../var/cache/twig');

