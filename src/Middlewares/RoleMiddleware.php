<?php

namespace stagify\Middlewares;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use stagify\Controllers\Controller;
use stagify\Shared;

class RoleMiddleware extends Shared implements MiddlewareInterface
{
    public function process(Request $request, RequestHandler $handler): Response
    {
        $role = $_SESSION["role"] ?? 0;
        $isAdmin = $role === 1;
        $isPilot = $role === 2;

        $canAccess = match ($request->getUri()->getPath()) {
            "/users/2",
            "/users/3"
            => $isPilot || $isAdmin,
            default => true,
        };

        if (!$canAccess) {
            FlashMiddleware::flash("warning", "Vous n'avez pas la permission d'accéder à cette page");
            return Controller::redirect($handler->handle($request), "/");
        }

        return $handler->handle($request);
    }
}