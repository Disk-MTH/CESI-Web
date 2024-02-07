<?php

namespace stagify;

use DI\ContainerBuilder;
use DI\DependencyException;
use DI\NotFoundException;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Doctrine\ORM\Tools\Console\EntityManagerProvider\SingleManagerProvider;
use Exception;

require __DIR__ . "/vendor/autoload.php";

$container = require __DIR__ . "/src/dependencies.php";
$container = $container();

ConsoleRunner::run(new SingleManagerProvider($container->get(EntityManager::class)));