<?php

namespace stagify;

use Psr\Container\ContainerInterface;
use Slim\App;
use Slim\Views\TwigMiddleware;
use stagify\Middlewares\ErrorsMiddleware;
use stagify\Middlewares\FlashMiddleware;
use stagify\Middlewares\OldDataMiddleware;

return function (App $app, ContainerInterface $container) {
    $twig = $container->get("twig");
    $settings = $container->get("settings");
    $logger = $container->get("logger");

    $app->add(FlashMiddleware::class);
    $app->add(OldDataMiddleware::class);
    $app->add(ErrorsMiddleware::class);
    $app->add(TwigMiddleware::create($app, $twig));
    $app->addRoutingMiddleware();
    $app->addBodyParsingMiddleware();
    $app->addErrorMiddleware($settings->get("displayErrorDetails"), $settings->get("logErrors"), $settings->get("logErrorDetails"), $logger);
};