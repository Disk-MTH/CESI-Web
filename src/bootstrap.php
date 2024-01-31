<?php

namespace stagify;

use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;

require_once "vendor/autoload.php";

function getEntityManager(): EntityManager
{
    $config = ORMSetup::createAttributeMetadataConfiguration(
        paths: array(__DIR__ . "/model"),
        isDevMode: true,
    );

    $params = array(
        "driver" => "pdo_mysql",
        "host" => "localhost",
        "dbname" => "stagify",
        "user" => "admin",
        "password" => "5Wp6A3wgdYgW54",
    );

    $connection = DriverManager::getConnection($params, $config);
    return new EntityManager($connection, $config);
}