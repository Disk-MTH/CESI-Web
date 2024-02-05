<?php

namespace stagify;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Doctrine\ORM\Tools\Console\EntityManagerProvider\SingleManagerProvider;

//require "vendor/autoload.php";
$container = require_once __DIR__ . "/app/bootstrap.php";

//$entityManager = getEntityManager();

ConsoleRunner::run($container[EntityManager::class]);