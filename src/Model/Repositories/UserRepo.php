<?php

namespace stagify\Model\Repositories;

use Doctrine\ORM\EntityRepository;
use Throwable;

final class UserRepo extends EntityRepository
{
    function pagination(int $page, int|null $role, bool $count, int $limit = 12): array|int
    {
        if (!$role) $role = 3;

        //TODO: apply filters
        $builder = $this->createQueryBuilder("u")
            ->select("u.id, u.firstName, u.lastName, u.profilePicturePath, l.city, l.zipCode, p.year, p.type, p.school")
            ->innerJoin("u.location", "l")
            ->innerJoin("u.promos", "p")
            ->where("u.deleted = 0")
            ->andWhere("u.role = :role")
            ->setParameter("role", $role);

        if ($count) {
            try {
                return array_sum(array_column($builder->select("COUNT(u)")->getQuery()->getScalarResult(), 1));
            } catch (Throwable) {
                return -1;
            }
        }

        return $builder
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    function getPilots(int $page, int $limit = 12): array
    {
        $query = $this->createQueryBuilder("u")
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