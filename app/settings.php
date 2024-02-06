<?php


use DI\ContainerBuilder;
use Monolog\Level;
use stagify\Settings\Settings;
use stagify\Settings\SettingsInterface;

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        SettingsInterface::class => function () {
            return new Settings([
                "displayErrorDetails" => true,
                "logError" => false,
                "logErrorDetails" => false,

                "logger" => [
                    "name" => "stagify",
                    "path" => __DIR__ . "/logs/app.log", //TODO: Change this to a more secure location
                    "level" => Level::Info,
                ],

                "doctrine" => [
                    "paths" => array(__DIR__ . "/../src/Model/Entities"),
                    "dev_mode" => true,
                    "connection" => [
                        "driver" => "pdo_mysql",
                        "host" => "localhost",
                        "dbname" => "stagify",
                        "user" => "admin",
                        "password" => "5Wp6A3wgdYgW54",
                    ],
                ],
            ]);
        },
    ]);
};