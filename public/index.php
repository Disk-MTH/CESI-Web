<?php

namespace stagify;

use Doctrine\ORM\EntityManager;
use Psr\Log\LoggerInterface;
use Slim\Factory\AppFactory;
use stagify\Settings\SettingsInterface;

require __DIR__ . "/../vendor/autoload.php";

$container = require __DIR__ . "/../src/dependencies.php";
$container = $container();

$logger = $container->get(LoggerInterface::class);
$logger->debug("Logger has been initialized");

$settings = $container->get(SettingsInterface::class);
$logger->debug("Settings has been initialized");

$entityManager = $container->get(EntityManager::class);
$logger->debug("EntityManager has been initialized");

AppFactory::setContainer($container);
$app = AppFactory::create();
$logger->debug("App has been initialized");

$routes = require __DIR__ . "/../src/routes.php";
$routes($app);
$logger->debug("Routes have been initialized");

$middlewares = require __DIR__ . "/../src/middlewares.php";
$middlewares($app);
$logger->debug("Middlewares have been initialized");

$app->addRoutingMiddleware();
$app->addBodyParsingMiddleware();
$app->addErrorMiddleware($settings->get("displayErrorDetails"), $settings->get("logErrors"), $settings->get("logErrorDetails"), $logger);
$logger->debug("Natives middlewares has been initialized");

$app->run();