<?php

namespace stagify\app;

use Slim\App;
use stagify\Middlewares\TestMiddleware;

return function (App $app) {
    $app->add(TestMiddleware::class);
};