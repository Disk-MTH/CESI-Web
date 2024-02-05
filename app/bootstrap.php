<?php

use DI\Container;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;

require __DIR__ . "/../vendor/autoload.php";

$container = new Container(require __DIR__ . "/settings.php");

$container[EntityManager::class] = function (Container $container): EntityManager {
    $settings = $container->get("settings");

    $config = ORMSetup::createAttributeMetadataConfiguration(
        paths: $settings["doctrine"]["paths"],
        isDevMode: $settings["doctrine"]["dev_mode"],
    );

    $connection = DriverManager::getConnection($settings["doctrine"]["connection"], $config);
    return new EntityManager($connection, $config);
};

return $container;