<?php


use DI\ContainerBuilder;
use stagify\Settings\SettingsMap;
use stagify\Settings\SettingsInterface;

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        SettingsInterface::class => function () {
            return new SettingsMap([
                "displayErrorDetails" => true,
                "logError"            => false,
                "logErrorDetails"     => false,

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