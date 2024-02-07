<?php

namespace stagify\Settings;

use Bramus\Monolog\Formatter\ColoredLineFormatter;
use Bramus\Monolog\Formatter\ColorSchemes\DefaultScheme;
use Monolog\Formatter\LineFormatter;
use Monolog\Level;

class Settings
{
    private array $settings;

    public function __construct(array $env)
    {
        $this->settings = [
            "displayErrorDetails" => $env["APP_DISPLAY_ERRORS_DETAILS"],
            "logErrors" => $env["APP_LOG_ERRORS"],
            "logErrorDetails" => $env["APP_LOG_ERROR_DETAILS"],

            "logger" => [
                "name" => $env["APP_NAME"],
                "level" => Level::fromName($env["APP_LOG_LEVEL"]),
                "filePath" => __DIR__ . "/../app.log",
                "fileFormatter" => new LineFormatter(null, "H:i:s:u", true, true),
                "consoleFormatter" => new ColoredLineFormatter(new ColorScheme(), null, "H:i:s:u", true, true),
            ],

            "doctrine" => [
                "paths" => array(__DIR__ . "/../src/Model/Entities"),
                "dev_mode" => $env["DB_DEV_MODE"],
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