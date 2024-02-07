<?php

namespace stagify;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;

return function (App $app) {
    $app->get("/", function (Request $request, Response $response) {
        global $logger;
        $logger->info("Get /");

        $response->getBody()->write("Hello world!");
        return $response;
    });

    $app->get("/hello/{name}", function (Request $request, Response $response, array $args) {
        $response->getBody()->write("Hello: " . $args["name"]);
        return $response;
    });
};