<?php

namespace stagify;

use DI\ContainerBuilder;
use DI\DependencyException;
use DI\NotFoundException;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Doctrine\ORM\Tools\Console\EntityManagerProvider\SingleManagerProvider;
use Exception;

require __DIR__ . "/vendor/autoload.php";

$containerBuilder = new ContainerBuilder();

//if (false) { // Should be set to true in production
//    $containerBuilder->enableCompilation(__DIR__ . "/var/cache");
//}

$settings = require __DIR__ . "/app/settings.php";
$settings($containerBuilder);

$dependencies = require __DIR__ . "/app/dependencies.php";
$dependencies($containerBuilder);

$container = null;
try {
    $container = $containerBuilder->build();
} catch (Exception $e) {
    //TODO: error
}

try {
    ConsoleRunner::run(new SingleManagerProvider($container->get(EntityManager::class)));
} catch (DependencyException|NotFoundException $e) {
    //TODO: error
}