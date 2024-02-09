<?php

namespace stagify;

use Slim\App;
use Slim\Views\TwigMiddleware;
use stagify\Middlewares\TestMiddleware;

return function (App $app) {
    global $logger, $settings, $twig;

    $app->add(TwigMiddleware::create($app, $twig));
    //$app->add(TestMiddleware::class);
    $app->addRoutingMiddleware();
    $app->addBodyParsingMiddleware();
    $app->addErrorMiddleware($settings->get("displayErrorDetails"), $settings->get("logErrors"), $settings->get("logErrorDetails"), $logger);
};