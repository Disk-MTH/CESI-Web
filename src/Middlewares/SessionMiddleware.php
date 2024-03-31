<?php

namespace stagify\Middlewares;

use DateTime;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use stagify\Controllers\Controller;
use stagify\Model\Entities\Session;
use stagify\Shared;
use Throwable;

class SessionMiddleware extends Shared implements MiddlewareInterface
{
    /** @throws Throwable */
    public function process(Request $request, RequestHandler $handler): Response
    {
        $sessionRepo = $this->entityManager->getRepository(Session::class);
        $session = null;

        if (isset($_SESSION["session"])) $session = $sessionRepo->findOneBy(["token" => $_SESSION["session"]]);
        else if (isset($_COOKIE["session"])) $session = $sessionRepo->findOneBy(["token" => $_COOKIE["session"]]);

        if ($session != null) {
            if ($session->getLastActivity() < new DateTime("-1 day")) {
                $session = null;
                $this->logOut();
                FlashMiddleware::flash("warning", "Session expirée, veuillez vous reconnecter");
            } else {
                $session->setLastActivity(new DateTime());
                $this->entityManager->flush();
            }
        }

        if ($session == null && $request->getUri()->getPath() != "/login") {
            FlashMiddleware::flash("warning", "Vous devez être connecté pour faire ceci");
            return Controller::redirect($handler->handle($request), "/login");
        } else if ($session != null && $request->getUri()->getPath() == "/login") {
            FlashMiddleware::flash("warning", "Vous êtes déjà connecté");
            return Controller::redirect($handler->handle($request), "/");
        }

        return $handler->handle($request);
    }

    public static function logIn(Session $session, bool $persist): void
    {
        $_SESSION["session"] = $session->getToken();
        if ($persist) setcookie("session", $session->getToken(), strtotime("+5 year"));
    }

    public static function logOut(): void
    {
        unset($_SESSION["session"]);
        setcookie("session", "", strtotime("-1 year"));
    }
}