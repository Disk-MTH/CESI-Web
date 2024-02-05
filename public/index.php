<?php

namespace stagify;

use Slim\Factory\AppFactory;

require __DIR__ . "/../vendor/autoload.php";

$app = AppFactory::create();
//$callableResolver = $app->getCallableResolver();

$middlewares = require __DIR__ . "/../app/middlewares.php";
$middlewares($app);

$routes = require __DIR__ . "/../app/routes.php";
$routes($app);

$app->addRoutingMiddleware();
$app->addBodyParsingMiddleware();
$errorMiddleware = $app->addErrorMiddleware(true, true, true);

$app->run();