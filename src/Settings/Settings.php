<?php

namespace stagify\Settings;

use Bramus\Monolog\Formatter\ColoredLineFormatter;
use Monolog\Formatter\LineFormatter;
use Monolog\Level;

class Settings implements SettingsInterface
{
    private array $settings;

    public function __construct(array $env)
    {
        $this->settings = [
            "debug" => $env["APP_DEBUG"],
            "displayErrorDetails" => $env["APP_DISPLAY_ERRORS_DETAILS"],
            "logErrors" => $env["APP_LOG_ERRORS"],
            "logErrorDetails" => $env["APP_LOG_ERROR_DETAILS"],

            "logger" => [
                "name" => $env["APP_NAME"],
                "level" => Level::fromName($env["APP_LOG_LEVEL"]),
                "filePath" => __DIR__ . "/../../logs/" . ($env["APP_LOG_PER_EXECUTION"] === "true" ? uniqid() : date("d-m-Y")) . ".log",
                "fileFormatter" => new LineFormatter(null, "d-m-Y - H:i:s:u", true, true),
                "consoleFormatter" => new ColoredLineFormatter(new ColorScheme(), null, "d-m-Y - H:i:s:u", true, true),
            ],

            "twig" => [
                "path" => __DIR__ . "/../../views/",
                "options" => [
                    "cache" => false,
                    //TODO: Add more options
                ],
            ],

            "doctrine" => [
                "paths" => array(__DIR__ . "/../Model/Entities"),
                "dev_mode" => $env["APP_DEBUG"],
                "connection" => [
                    "driver" => $env["DB_DRIVER"],
                    "host" => $env["DB_HOST"],
                    "dbname" => $env["DB_DATABASE"],
                    "charset" => $env["DB_CHARSET"],
                    "user" => $env["DB_USER"],
                    "password" => $env["DB_PASSWORD"],
                ],
            ],
        ];
    }

    public function get(string $key = ""): mixed
    {
        return (empty($key)) ? $this->settings : $this->settings[$key];
    }
}