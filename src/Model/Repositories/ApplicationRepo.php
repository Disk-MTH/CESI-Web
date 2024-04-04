<?php

namespace stagify\Model\Repositories;

use Doctrine\ORM\EntityRepository;

class ApplicationRepo extends EntityRepository
{
    function byUserId(int $userId): array
    {
        return $this->createQueryBuilder("a")
            ->select("a.accepted, i.id AS internshipId, i.title AS internshipTitle")
            ->innerJoin("a.internship", "i")
            ->innerJoin("i.location", "l")
            ->where("a.user = :userId")
            ->setParameter("userId", $userId)
            ->getQuery()
            ->getArrayResult();
    }
}