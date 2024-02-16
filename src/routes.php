<?php

namespace stagify;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Views\Twig;

return function (App $app) {
    $app->get("/", function (Request $request, Response $response) {
        return Twig::fromRequest($request)->render($response, "home.twig");
    })->setName("home");

    $app->get("/base", function (Request $request, Response $response) {
        return Twig::fromRequest($request)->render($response, "templates/base.twig");
    })->setName("base");

    $app->get("/login", function (Request $request, Response $response) {
        return Twig::fromRequest($request)->render($response, "login.twig");
    })->setName("login");

    $app->get("/user", function (Request $request, Response $response) {
        return Twig::fromRequest($request)->render($response, "user.twig");
    })->setName("user");

    $app->get("/tos", function (Request $request, Response $response) {
        return Twig::fromRequest($request)->render($response, "tos.twig");
    })->setName("tos");

    $app->get("/company", function (Request $request, Response $response) {
        return Twig::fromRequest($request)->render($response, "company.twig");
    })->setName("company");
};