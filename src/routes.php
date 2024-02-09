<?php

namespace stagify;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Views\Twig;

return function (App $app) {
    $app->get("/", function (Request $request, Response $response) {
        return Twig::fromRequest($request)->render($response, "index.twig");
    })->setName("home");

    $app->get("/login", function (Request $request, Response $response) {
        return Twig::fromRequest($request)->render($response, "login.twig");
    })->setName("login");
};