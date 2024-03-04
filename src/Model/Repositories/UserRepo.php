<?php

namespace stagify\Model\Repositories;

use DI\Container;
use Doctrine\ORM\EntityManager;
use stagify\Model\Entities\temp\User;

$container[UserRepo::class] = function (Container $container) {
    return new UserRepo($container[EntityManager::class]);
};

final class UserRepo
{
    private EntityManager $entityManager;

    public function __construct(EntityManager $orm)
    {
        $this->entityManager = $orm;
    }

    public function usersAsArray(): array
    {
        $users = $this->entityManager->getRepository(User::class)->findAll();
        $usersArray = [];
        foreach ($users as $user) {
            $usersArray[] = $user->toArray();
        }
        return $usersArray;
    }
}