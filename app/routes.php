<?php

namespace stagify\app;

use Doctrine\ORM\EntityManager;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Log\LoggerInterface;
use Slim\App;
use stagify\Model\Entities\User;
use stagify\Model\Repositories\UserRepo;

return function (App $app) {
    $app->get("/", function (Request $request, Response $response) {
        $response->getBody()->write("Hello world!");

        //TODO: use app logger to log content of user table with doctrine

        $userRepo = require __DIR__ . "/../src/Model/Repositories/UserRepo.php";
        $userRepo->usersAsArray();
        //$users = $userRepo->usersAsArray();
        //$response->getBody()->write(json_encode($users));

        return $response;
    });

    $app->get("/hello/{name}", function (Request $request, Response $response, array $args) {
        $name = $args["name"];
        $response->getBody()->write("Hello, $name");
        return $response;
    });
};