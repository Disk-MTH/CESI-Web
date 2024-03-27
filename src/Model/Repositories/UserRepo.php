<?php

namespace stagify\Model\Repositories;

use Doctrine\ORM\EntityRepository;

final class UserRepo extends EntityRepository
{
    function getStudents(int $page, int $limit = 12): array
    {
        $query = $this->createQueryBuilder('u')
            ->select("u.id, u.firstName, u.lastName, u.profilePicturePath, l.city, l.zipCode, p.year, p.type, p.school")
            ->innerJoin("u.location", "l")
            ->innerJoin("u.promos", "p")
            ->where("u.deleted = 0")
            ->andWhere("u.role = 3")
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit)
            ->getQuery();

        return $query->getResult();
    }

    function getPilots(int $page, int $limit = 12): array
    {
        $query = $this->createQueryBuilder('u')
            ->select("u.id, u.firstName, u.lastName, u.profilePicturePath, l.city, l.zipCode, p.year, p.type, p.school")
            ->innerJoin("u.location", "l")
            ->innerJoin("u.promos", "p")
            ->where("u.deleted = 0")
            ->andWhere("u.role = 2")
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit)
            ->getQuery();

        return $query->getResult();
    }
}