<?php

namespace stagify;

use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Doctrine\ORM\Tools\Console\EntityManagerProvider\SingleManagerProvider;

require "vendor/autoload.php";

$entityManager = getEntityManager();

ConsoleRunner::run(
    new SingleManagerProvider($entityManager)
);