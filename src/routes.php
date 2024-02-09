<?php

namespace stagify;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Views\Twig;

return function (App $app) {
    $app->get("/", function (Request $request, Response $response) {
        $view = Twig::fromRequest($request);
        return $view->render($response, "index.twig");
    });

    $app->get("/login", function (Request $request, Response $response, array $args) {
        $view = Twig::fromRequest($request);
        return $view->render($response, "login.twig");
    });
};