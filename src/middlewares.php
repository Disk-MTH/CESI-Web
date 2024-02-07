<?php

namespace stagify;

use Slim\App;
use stagify\Middlewares\TestMiddleware;

return function (App $app) {
    $app->add(TestMiddleware::class);
};