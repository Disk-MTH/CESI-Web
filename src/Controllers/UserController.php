<?php

namespace stagify\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class UserController extends Controller
{
    function users(Request $request, Response $response, array $pathArgs): Response
    {
        if ($pathArgs["role"] == 2) {
            return $this->render($response, "pages/pilots.twig");
        }

        return $this->render($response, "pages/users.twig");
    }

    function user(Request $request, Response $response, array $pathArgs): Response
    {
        return $this->render($response, "pages/user.twig");
    }

    function wishlist(Request $request, Response $response): Response
    {
        return $this->render($response, "pages/wishlist.twig");
    }

    function createUser(Request $request, Response $response, array $pathArgs): Response
    {
        if ($pathArgs["role"] == 2) {
            return $this->render($response, "pages/create_pilot.twig");
        }

        return $this->render($response, "pages/create_user.twig");
    }
}