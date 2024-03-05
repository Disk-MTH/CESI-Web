<?php

namespace stagify;

use Doctrine\ORM\EntityManager;
use Psr\Log\LoggerInterface;
use Slim\Factory\AppFactory;
use Slim\Views\Twig; 
use stagify\Settings\SettingsInterface;

require __DIR__ . "/../vendor/autoload.php";

session_start();

$container = require __DIR__ . "/../src/dependencies.php";
$container = $container();

/** @var LoggerInterface $logger */
$logger = $container->get(LoggerInterface::class);
$logger->debug("Logger has been initialized");

/** @var SettingsInterface $settings */
$settings = $container->get(SettingsInterface::class);
$logger->debug("Settings has been initialized");

/** @var Twig $twig */
$twig = $container->get(Twig::class);
$logger->debug("Twig has been initialized");

/** @var EntityManager $entityManager */
$entityManager = $container->get(EntityManager::class);
$logger->debug("EntityManager has been initialized");

AppFactory::setContainer($container);
$app = AppFactory::create();
$logger->debug("App has been initialized");

$routes = require __DIR__ . "/../src/routes.php";
$routes($app, $logger, $entityManager);
$logger->debug("Routes have been initialized");

$middlewares = require __DIR__ . "/../src/middlewares.php";
$middlewares($app);
$logger->debug("Middlewares have been initialized");

$app->run();