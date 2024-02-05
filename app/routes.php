<?php

namespace stagify\app;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;

return function (App $app) {
    $app->get("/y", function (Request $request, Response $response) {
        $response->getBody()->write("Hello world!");
        return $response;
    });

    $app->get("/hello/{name}", function (Request $request, Response $response, array $args) {
        $name = $args["name"];
        $response->getBody()->write("Hello, $name");
        return $response;
    });
};