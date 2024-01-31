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
        'driver' => 'pdo_mysql',
        'host' => 'localhost',
        'dbname' => 'stagify',
        'user' => 'root',
        'password' => '8jHe3Ru3N3q6jX',
    );

    $connection = DriverManager::getConnection($params, $config);
    return new EntityManager($connection, $config);
}

/*try {
    $connection = DriverManager::getConnection($params, $config);
    $entityManager = new EntityManager($connection, $config);


} catch (Exception|MissingMappingDriverImplementation $e) {
    echo $e->getMessage();
}*/