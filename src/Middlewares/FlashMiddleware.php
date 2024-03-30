<?php

namespace stagify\Middlewares;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use stagify\Shared;

class FlashMiddleware extends Shared implements MiddlewareInterface
{
    public function process(Request $request, RequestHandler $handler): Response
    {
        $this->twig->offsetSet("flash", $_SESSION["flash"] ?? null);

        if (isset($_SESSION["flash"])) {
            unset($_SESSION["flash"]);
        }

        return $handler->handle($request);
    }

    public static function flash(string $status, string $message): void
    {
        $_SESSION["flash"] = [
            "status" => $status,
            "message" => $message
        ];
    }
}