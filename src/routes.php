<?php

namespace stagify;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Views\Twig;
use stagify\Model\Entities\User;

function redirect(Response $response, string $name, array $args = []) : Response
{
    global $app;
    return $response->withStatus(302)->withHeader("Location", $app->getRouteCollector()->getRouteParser()->urlFor($name, $args));
}

return function (App $app) {
    $app->get("/list", function (Request $request, Response $response) {
        global $entityManager;
        $users = $entityManager->getRepository(User::class)->findAll();
        return Twig::fromRequest($request)->render($response, "temp/list.twig", ["users" => $users]);
    })->setName("list");

    $app->get("/form", function (Request $request, Response $response) {
        return Twig::fromRequest($request)->render($response, "temp/form.twig");
    })->setName("form");

    $app->post("/form", function (Request $request, Response $response) {
        global $entityManager;

        $data = $request->getParsedBody();
        $user = (new User())
            ->setFirstName($data["firstName"])
            ->setLastName($data["lastName"])
            ->setProfilePicturePath($data["profilePicturePath"])
            ->setLogin($data["login"])
            ->setPasswordHash($data["passwordHash"])
            ->setDeleted(false);

        $entityManager->persist($user);
        $entityManager->flush();

        return redirect($response, "list");
    });

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

    $app->get("/user/wishlist", function (Request $request, Response $response) {
        return Twig::fromRequest($request)->render($response, "wishlist.twig");
    })->setName("wishlist");

    $app->get("/tos", function (Request $request, Response $response) {
        return Twig::fromRequest($request)->render($response, "tos.twig");
    })->setName("tos");

    $app->get("/company", function (Request $request, Response $response) {
        return Twig::fromRequest($request)->render($response, "company.twig");
    })->setName("company");

    $app->get("/companies", function (Request $request, Response $response) {
        return Twig::fromRequest($request)->render($response, "companies.twig");
    })->setName("companies");
};