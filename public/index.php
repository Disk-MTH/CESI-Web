<?php

namespace stagify;

use DI\Container;
use DI\ContainerBuilder;
use DI\DependencyException;
use DI\NotFoundException;
use Exception;
use Slim\Factory\AppFactory;
use stagify\Settings\SettingsMap;
use stagify\Settings\SettingsInterface;

require __DIR__ . "/../vendor/autoload.php";

$containerBuilder = new ContainerBuilder();

$settings = require __DIR__ . "/../app/settings.php";
$settings($containerBuilder);

$container = new Container();
try {
    $container = $containerBuilder->build();
} catch (Exception $e) {
    //TODO: error
}

AppFactory::setContainer($container);
$app = AppFactory::create();

$middlewares = require __DIR__ . "/../app/middlewares.php";
$middlewares($app);

$routes = require __DIR__ . "/../app/routes.php";
$routes($app);

try {
    $settings = $container->get(SettingsInterface::class);
} catch (DependencyException|NotFoundException $e) {
    //TODO: error
}

$displayErrorDetails = $settings->get("displayErrorDetails");
$logError = $settings->get("logError");
$logErrorDetails = $settings->get("logErrorDetails");

$app->addRoutingMiddleware();
$app->addBodyParsingMiddleware();

$errorMiddleware = $app->addErrorMiddleware($displayErrorDetails, $logError, $logErrorDetails);

$app->run();