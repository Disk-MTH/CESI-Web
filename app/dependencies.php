<?php

use DI\ContainerBuilder;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\UidProcessor;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use stagify\Settings\SettingsInterface;

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        LoggerInterface::class => function (ContainerInterface $container) {
            $loggerSettings = $container->get(SettingsInterface::class)->get("logger");

            $logger = new Logger($loggerSettings["name"]);
            $logger->pushProcessor(new UidProcessor());
            $logger->pushHandler(new StreamHandler($loggerSettings["path"], $loggerSettings["level"]));

            return $logger;
        },
        EntityManager::class => function (ContainerInterface $container) {
            $ormSettings = $container->get(SettingsInterface::class)->get("doctrine");

            $config = ORMSetup::createAttributeMetadataConfiguration(
                paths: $ormSettings["paths"],
                isDevMode: $ormSettings["dev_mode"],
            );
            $connection = DriverManager::getConnection($ormSettings["connection"], $config);

            return new EntityManager($connection, $config);
        }
    ]);
};