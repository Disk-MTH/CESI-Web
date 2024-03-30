<?php

namespace stagify\Controllers;

use DateTime;
use Psr\Http\Message\ResponseInterface as Response;
use stagify\Shared;
use stagify\Middlewares\FlashMiddleware;
use stagify\Model\Entities\Session;
use stagify\Model\Entities\User;
use Throwable;
use Twig\Error\Error;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class Controller extends Shared
{
    static function redirect(Response $response, string $url): Response
    {
        return $response->withStatus(302)->withHeader("Location", $url);
    }

    function render(Response $response, string $template, array $data = []): Response
    {
        //$data["user"] = $this->entityManager->getRepository(User::class)->findOneBy(["id" => $_SESSION["user"]]);
        try {
            return $this->twig->render($response, $template, $data);
        } catch (Error $error) {
            $response->getBody()->write($error->getMessage());
            return $response->withStatus(500);
        }
    }

    function json(Response $response, array $data): Response
    {
        $response->getBody()->write(json_encode($data));
        return $response->withHeader("Content-Type", "application/json");
    }
}