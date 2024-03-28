<?php

namespace stagify\Controllers;

use DateTime;
use Psr\Http\Message\ResponseInterface as Response;
use stagify\Container;
use stagify\Middlewares\FlashMiddleware;
use stagify\Model\Entities\Session;
use stagify\Model\Entities\User;

class Controller extends Container
{
    function render(Response $response, string $template, array $data = []): Response
    {
        $sessionRepo = $this->entityManager->getRepository(Session::class);
        $session = $sessionRepo->findOneBy(["token" => $_COOKIE["session"] ?? ""]);

        if ($session != null) {
            if ($session->getLastActivity() < new DateTime("-" . Session::$duration)) {
                $session = null;
                Session::logOut();
                FlashMiddleware::flash("warning", "Session expirée, veuillez vous reconnecter");
            } else {
                $session->setLastActivity(new DateTime());
                $this->entityManager->flush();
                Session::logIn($session);
            }
        }

        if ($session == null && $template !== "pages/login.twig") {
            return $this->redirect($response, "login");
        } else if ($session != null && $template === "pages/login.twig") {
            return $this->redirect($response, "/");
        }

        if ($session != null) {
            $data["user"] = $this->entityManager->getRepository(User::class)->findOneBy(["id" => $_SESSION["user"]]);
        }

        return $this->twig->render($response, $template, $data);
    }

    function redirect(Response $response, string $url): Response
    {
        return $response->withStatus(302)->withHeader("Location", $url);
    }

    function json(Response $response, array $data): Response
    {
        $response->getBody()->write(json_encode($data));
        return $response->withHeader("Content-Type", "application/json");
    }
}