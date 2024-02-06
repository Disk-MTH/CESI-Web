<?php

namespace stagify;

use Bramus\Monolog\Formatter\ColoredLineFormatter;
use DI\Container;
use DI\ContainerBuilder;
use DI\DependencyException;
use DI\NotFoundException;
use Dotenv\Dotenv;
use Exception;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\FirePHPHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\LogRecord;
use Psr\Log\LoggerInterface;
use Slim\Exception\HttpNotFoundException;
use Slim\Factory\AppFactory;
use stagify\Settings\Settings;
use stagify\Settings\SettingsInterface;
use function DI\create;

require __DIR__ . "/../vendor/autoload.php";

/*$containerBuilder = new ContainerBuilder();

$settings = require __DIR__ . "/../app/settings.php";
$settings($containerBuilder);

$dependencies = require __DIR__ . "/../app/dependencies.php";
$dependencies($containerBuilder);

$container = new Container();
try {
    $container = $containerBuilder->build();
} catch (Exception $e) {
    //TODO: error
}*/

Dotenv::createImmutable(__DIR__ . "/../")->load();
$settings = new Settings($_ENV);

$container = new Container();
$container->set(Settings::class, $settings);
$container->set(LoggerInterface::class, function () {
    global $settings;
    $loggerSettings = $settings->get("logger");

    $logger = new Logger($loggerSettings["name"]);
    $stream = new StreamHandler($loggerSettings["path"]);
    $stream->setFormatter($loggerSettings["formatter"]);
    $logger->pushHandler($stream);

    return $logger;
});

$logger = $container->get(LoggerInterface::class);
$logger->debug("The logger has been loaded");
$logger->info("The logger has been loaded");
$logger->notice("The logger has been loaded");
$logger->warning("The logger has been loaded");
$logger->error("The logger has been loaded");
$logger->critical("The logger has been loaded");
$logger->alert("The logger has been loaded");
$logger->emergency("The logger has been loaded");

AppFactory::setContainer($container);
$app = AppFactory::create();

$middlewares = require __DIR__ . "/../app/middlewares.php";
$middlewares($app);

//$routes = require __DIR__ . "/../app/routes.php";
//$routes($app);

$app->get("/y", function ($request, $response) {
    $response->getBody()->write("Hello world!");
    return $response;
});

/*try {
    $settings = $container->get(SettingsInterface::class);
} catch (DependencyException|NotFoundException $e) {
    //TODO: error
}

$logger = null;
try {
    $logger = $container->get(LoggerInterface::class);
    $logger->info("The logger has been loaded");
} catch (DependencyException|NotFoundException $e) {
    //TODO: error
}*/

/*$displayErrorDetails = $settings->get("displayErrorDetails");
$logError = $settings->get("logError");
$logErrorDetails = $settings->get("logErrorDetails");*/

$app->addRoutingMiddleware();
$app->addBodyParsingMiddleware();

$errorMiddleware = $app->addErrorMiddleware($settings->get("displayErrorDetails"), $settings->get("logErrors"), $settings->get("logErrorDetails"), $logger);


//$logger->info("The app started successfully");

$app->run();