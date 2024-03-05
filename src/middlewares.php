<?php

namespace stagify;

use Slim\App;
use Slim\Views\TwigMiddleware;
use stagify\Middlewares\FlashMiddleware;

return function (App $app) {
    global $logger, $settings, $twig;

    $app->add(TwigMiddleware::create($app, $twig));
    $app->add(FlashMiddleware::class);
    $app->addRoutingMiddleware();
    $app->addBodyParsingMiddleware();
    $app->addErrorMiddleware($settings->get("displayErrorDetails"), $settings->get("logErrors"), $settings->get("logErrorDetails"), $logger);
};