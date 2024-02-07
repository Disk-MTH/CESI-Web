<?php

namespace stagify\app;

use Doctrine\ORM\EntityManager;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Log\LoggerInterface;
use Slim\App;
use stagify\Model\Entities\User;
use stagify\Model\Repositories\UserRepo;
use stagify\Settings\Settings;

return function (App $app) {
    $settings = $app->getContainer()->get(Settings::class);
    $logger = $app->getContainer()->get(LoggerInterface::class);

    $app->get("/", function (Request $request, Response $response) {
        global $logger;
        $logger->info("Get /");

        $response->getBody()->write("Hello world!");
        return $response;
    });

    $app->get("/hello/{name}", function (Request $request, Response $response, array $args) {
        $name = $args["name"];
        $response->getBody()->write("Hello, $name");
        return $response;
    });
};