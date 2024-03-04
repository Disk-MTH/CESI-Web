<?php

namespace stagify\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Views\Twig;

class Controller
{
    private $app;

    public function __construct(App $app)
    {
        $this->app = $app;
    }

    public function render(Request $request, Response $response, String $view, array $data = []) : Response
    {
        return Twig::fromRequest($response)->render($response, $view, $data);
    }
}