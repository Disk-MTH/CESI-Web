<?php

namespace stagify\Middlewares;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use stagify\Shared;

class OldDataMiddleware extends Shared implements MiddlewareInterface
{
    public function process(Request $request, RequestHandler $handler): Response
    {
        $this->twig->offsetSet("old", $_SESSION["old"] ?? null);

        if (isset($_SESSION["old"])) {
            unset($_SESSION["old"]);
        }

        $response = $handler->handle($request);

        if ($response->getStatusCode() !== 200) {
            $_SESSION["old"] = $request->getParsedBody();
        }

        return $response;
    }
}
