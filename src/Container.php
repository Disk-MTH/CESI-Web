<?php

namespace stagify;

use Doctrine\ORM\EntityManager;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Slim\Views\Twig;
use stagify\Settings\Settings;
use Throwable;

class Container
{
    protected Settings $settings;
    protected LoggerInterface $logger;
    protected Twig $twig;
    protected EntityManager $entityManager;

    /** @throws Throwable */
    public function __construct(ContainerInterface $container)
    {
        $this->settings = $container->get("settings");
        $this->logger = $container->get("logger");
        $this->twig = $container->get("twig");
        $this->entityManager = $container->get("entityManager");
    }
}