<?php

namespace stagify;

use DI\Container;
use DI\ContainerBuilder;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;
use Dotenv\Dotenv;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Slim\Views\Twig;
use stagify\Settings\Settings;
use stagify\Settings\SettingsInterface;

return function (): Container {
    Dotenv::createImmutable(__DIR__ . "/../")->load();
    $containerBuilder = new ContainerBuilder();

    $containerBuilder->addDefinitions([
        SettingsInterface::class => function () {
            return new Settings($_ENV);
        },

        LoggerInterface::class => function (ContainerInterface $container) {
            $loggerSettings = $container->get(SettingsInterface::class)->get("logger");
            $logger = new Logger($loggerSettings["name"]);

            $file = new StreamHandler($loggerSettings["filePath"], $loggerSettings["level"]);
            $file->setFormatter($loggerSettings["fileFormatter"]);
            $logger->pushHandler($file);

            $console = new StreamHandler("php://stdout", $loggerSettings["level"]);
            $console->setFormatter($loggerSettings["consoleFormatter"]);
            $logger->pushHandler($console);
            $logger->info("Log file path: " . $loggerSettings["filePath"]);

            return $logger;
        },

        Twig::class => function (ContainerInterface $container) {
            $twigSettings = $container->get(SettingsInterface::class)->get("twig");
            return Twig::create($twigSettings["path"], $twigSettings["options"]);
        },

        EntityManager::class => function (ContainerInterface $container) {
            $ormSettings = $container->get(SettingsInterface::class)->get("doctrine");

            $config = ORMSetup::createAttributeMetadataConfiguration(
                paths: $ormSettings["paths"],
                isDevMode: $ormSettings["dev_mode"],
            );
            $connection = DriverManager::getConnection($ormSettings["connection"], $config);

            return new EntityManager($connection, $config);
        },

        "fileDirectory" => __DIR__ . "/../public/files",
    ]);

    if ($_ENV["APP_DEBUG"] === "false") {
        $containerBuilder->enableCompilation(__DIR__ . "/../cache");
    }
    return $containerBuilder->build();

};