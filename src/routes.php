<?php

namespace stagify;

use Doctrine\ORM\EntityManager;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Log\LoggerInterface as Logger;
use Slim\App;
use stagify\Flash\Flash;
use stagify\Flash\FlashStatus;
use stagify\Flash\FlashType;
use stagify\Model\Entities\User;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

function redirect(Response $response, string $url): Response
{
    return $response->withStatus(302)->withHeader("Location", $url);
}

/**
 * @throws SyntaxError
 * @throws RuntimeError
 * @throws LoaderError
 */
function render(Response $response, string $template, array $data = []): Response
{
    global $twig;
    return $twig->render($response, $template, $data);
}

return function (App $app, Logger $logger, EntityManager $entityManager) {
    $app->get("/list", function (Request $request, Response $response) use ($entityManager) {
        $users = $entityManager->getRepository(User::class)->findAll();
        return render($response, "temp/list.twig", ["users" => $users]);
    })->setName("list");

    $app->get("/form", function (Request $request, Response $response) use ($logger) {
        return render($response, "temp/form.twig");
    })->setName("form");

    $app->post("/form", function (Request $request, Response $response) use ($entityManager) {
        $_SESSION["flash"] = new Flash("User has been created", FlashStatus::success);

        /*$data = $request->getParsedBody();
        $user = (new User())
            ->setFirstName($data["firstName"])
            ->setLastName($data["lastName"])
            ->setProfilePicturePath($data["profilePicturePath"])
            ->setLogin($data["login"])
            ->setPasswordHash($data["passwordHash"])
            ->setDeleted(false);

        $entityManager->persist($user);
        $entityManager->flush();*/

//        return redirect($response, "list");
        return redirect($response, "form");
    });

    $app->get("/", function (Request $request, Response $response) {
        return render($response, "home.twig");
    })->setName("home");

    $app->get("/base", function (Request $request, Response $response) {
        return render($response, "base.twig");
    })->setName("base");

    $app->get("/login", function (Request $request, Response $response) {
        return render($response, "login.twig");
    })->setName("login");

    $app->get("/user", function (Request $request, Response $response) {
        return render($response, "user.twig");
    })->setName("user");

    $app->get("/user/wishlist", function (Request $request, Response $response) {
        return render($response, "wishlist.twig");
    })->setName("wishlist");

    $app->get("/tos", function (Request $request, Response $response) {
        return render($response, "tos.twig");
    })->setName("tos");

    $app->get("/company", function (Request $request, Response $response) {
        return render($response, "company.twig");
    })->setName("company");

    $app->get("/companies", function (Request $request, Response $response) {
        return render($response, "companies.twig");
    })->setName("companies");

    $app->get("/jobs", function (Request $request, Response $response) {
        return render($response, "jobs.twig");
    })->setName("internships");

    $app->get("/users", function (Request $request, Response $response) {
        return render($response, "users.twig");
    })->setName("users");
};