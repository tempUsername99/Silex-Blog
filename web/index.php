<?php

ini_set('display_errors', 0);

//Dependencies
require_once __DIR__.'/../vendor/autoload.php';

//database connection
require __DIR__.'/../src/bootstrap.php';

//ServiceProviders Registrar
$app = require __DIR__.'/../src/app.php';

//app bindings
require __DIR__.'/../config/prod.php';

//controllers
require __DIR__.'/../src/controllers.php';

$app->run();
