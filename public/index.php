<?php

namespace stagify;

use Slim\Factory\AppFactory;

require __DIR__ . "/../vendor/autoload.php";

session_start();

$container = require __DIR__ . "/../src/dependencies.php";
$container = $container();

AppFactory::setContainer($container);
$app = AppFactory::create();

$middlewares = require __DIR__ . "/../src/middlewares.php";
$middlewares($app, $container);

$routes = require __DIR__ . "/../src/routes.php";
$routes($app);

$app->run();