<?php

namespace stagify\Model\Repositories;

use Doctrine\ORM\EntityRepository;

final class LocationRepo extends EntityRepository
{
    function findByCompany(int $companyId): array
    {
        $query = $this->createQueryBuilder("l")
            ->select("l")
            ->join("l.companies", "c")
            ->where("c.id = :companyId")
            ->setParameter("companyId", $companyId)
            ->getQuery();

        return $query->getResult();
    }
}