<?php

use Doctrine\ORM\EntityManager;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Log\LoggerInterface as Logger;
use Slim\App;
use Slim\Views\Twig;
use function stagify\render;

return function (App $app, Logger $logger, Twig $twig, EntityManager $entityManager, string $fileDirectory) {
    $app->get("/internship", function (Request $request, Response $response) {
        return render($response, "pages/internship.twig");
    })->setName("internship");

    $app->get("/company", function (Request $request, Response $response) {
        return render($response, "pages/company.twig");
    })->setName("company");

    $app->get("/user", function (Request $request, Response $response) {
        return render($response, "pages/user.twig");
    })->setName("user");
};