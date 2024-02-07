<?php

namespace stagify;

use DI\Container;
use Dotenv\Dotenv;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;
use Slim\Factory\AppFactory;
use stagify\Settings\Settings;

require __DIR__ . "/../vendor/autoload.php";

Dotenv::createImmutable(__DIR__ . "/../")->load();
$settings = new Settings($_ENV);

$container = new Container();
$container->set(Settings::class, $settings);
$container->set(LoggerInterface::class, function () {
    global $settings;
    $loggerSettings = $settings->get("logger");
    $logger = new Logger($loggerSettings["name"]);

    $file = new StreamHandler($loggerSettings["filePath"], $loggerSettings["level"]);
    $file->setFormatter($loggerSettings["fileFormatter"]);
    $logger->pushHandler($file);

    $console = new StreamHandler("php://stdout", $loggerSettings["level"]);
    $console->setFormatter($loggerSettings["consoleFormatter"]);
    $logger->pushHandler($console);

    return $logger;
});

$logger = $container->get(LoggerInterface::class);
$logger->info("Logger has been initialized");

AppFactory::setContainer($container);
$app = AppFactory::create();
$logger->info("App has been initialized");

$routes = require __DIR__ . "/../src/routes.php";
$routes($app);
$logger->info("Routes have been initialized");

$middlewares = require __DIR__ . "/../src/middlewares.php";
$middlewares($app);
$logger->info("Middlewares have been initialized");

$app->addRoutingMiddleware();
$app->addBodyParsingMiddleware();
$app->addErrorMiddleware($settings->get("displayErrorDetails"), $settings->get("logErrors"), $settings->get("logErrorDetails"), $logger);
$logger->info("Natives middlewares has been initialized");

$app->run();
$logger->info("App has been run");