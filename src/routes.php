<?php

namespace stagify;

use Doctrine\ORM\EntityManager;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Views\Twig;
use stagify\Model\Entities\User;

return function (App $app) {


    $app->get("/temp", function (Request $request, Response $response) {
        global $entityManager;

        //get the user objet with doctrine and pass it to the view

        $user = $entityManager->getRepository(User::class)->findAll();


        for ($i = 0; $i < count($user); $i++) {
            $response->getBody()->write($user[$i]);
        }
    })->setName("temp");

    /*$app->post("/temp", function (Request $request, Response $response) {
        var_dump($request->getQueryParams());
        return Twig::fromRequest($request)->render($response, "temp.twig");
    });*/


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

    $app->get("/user/wishlist", function (Request $request, Response $response) {
        return Twig::fromRequest($request)->render($response, "wishlist.twig");
    })->setName("wishlist");
};