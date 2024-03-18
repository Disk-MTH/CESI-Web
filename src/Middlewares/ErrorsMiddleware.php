<?php

namespace stagify\Middlewares;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class ErrorsMiddleware implements MiddlewareInterface
{
    public function process(Request $request, RequestHandler $handler): Response
    {
        global $twig;
        $twig->offsetSet("errors", $_SESSION["errors"] ?? null);

        if (isset($_SESSION["errors"])) {
            unset($_SESSION["errors"]);
        }

        return $handler->handle($request);
    }

    public static function error(array $errors): void
    {
        $_SESSION["errors"] = $errors;
    }
}