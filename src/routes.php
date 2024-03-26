<?php

namespace stagify;

use DateTime;
use Doctrine\ORM\EntityManager;
use Exception;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Log\LoggerInterface as Logger;
use Slim\App;
use Slim\Psr7\UploadedFile;
use Slim\Views\Twig;
use stagify\Middlewares\FlashMiddleware;
use stagify\Model\Entities\Session;
use stagify\Model\Entities\User;

/**
 * @throws Exception
 */
function render(Response $response, string $template, array $data = []): Response
{
    global $twig;
    global $entityManager;

    $sessionRepo = $entityManager->getRepository(Session::class);
    $session = $sessionRepo->findOneBy(["token" => $_COOKIE["session"] ?? ""]);

    if ($session != null) {
        if ($session->getLastActivity() < new DateTime("-" . Session::$duration)) {
            $session = null;
            Session::logOut();
            FlashMiddleware::flash("warning", "Session expirée, veuillez vous reconnecter");
        } else {
            $session->setLastActivity(new DateTime());
            $entityManager->flush();
            Session::logIn($session);
        }
    }

    if ($session == null && $template !== "pages/login.twig") {
        return redirect($response, "login");
    } else if ($session != null && $template === "pages/login.twig") {
        return redirect($response, "/");
    }

    if ($session != null) {
        $data["user"] = $entityManager->getRepository(User::class)->findOneBy(["id" => $_SESSION["user"]]);
    }

    return $twig->render($response, $template, $data);
}

function redirect(Response $response, string $url): Response
{
    return $response->withStatus(302)->withHeader("Location", $url);
}

//todo: remake
function moveUploadedFile($directory, UploadedFile $uploadedFile): string
{
    $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);
    $basename = bin2hex(random_bytes(8)); // see http://php.net/manual/en/function.random-bytes.php
    $filename = sprintf('%s.%0.8s', $basename, $extension);

    $uploadedFile->moveTo($directory . DIRECTORY_SEPARATOR . $filename);

    return $filename;
}

return function (App $app, Logger $logger, Twig $twig, EntityManager $entityManager, string $fileDirectory) {
    $app->get("/", function (Request $request, Response $response) use ($entityManager, $logger) {
        return render($response, "pages/home.twig");
    })->setName("home");

    $app->get("/tos", function (Request $request, Response $response) {
        return render($response, "pages/tos.twig");
    })->setName("tos");

    $app->get("/offline", function (Request $request, Response $response) use ($twig) {
        return $twig->render($response, "pages/offline.twig");
    })->setName("offline");

    $session = require __DIR__ . "/Routes/session.php";
    $session($app, $logger, $twig, $entityManager);

    $listing = require __DIR__ . "/Routes/listing.php";
    $listing($app, $logger, $twig, $entityManager, $fileDirectory);

    $info = require __DIR__ . "/Routes/info.php";
    $info($app, $logger, $twig, $entityManager, $fileDirectory);

    $form = require __DIR__ . "/Routes/form.php";
    $form($app, $logger, $twig, $entityManager, $fileDirectory);

    $endpoints = require __DIR__ . "/Routes/endpoints.php";
    $endpoints($app, $logger, $twig, $entityManager);
};