<?php

namespace stagify\Model\Repositories;

use Doctrine\ORM\EntityRepository;
use stagify\Model\Entities\Company;
use stagify\Model\Entities\Location;
use Throwable;

final class LocationRepo extends EntityRepository
{
    function byCompany(Company $company): array
    {
        $query = $this->createQueryBuilder("l")
            ->select("l")
            ->join("l.companies", "c")
            ->where("c.id = :companyId")
            ->setParameter("companyId", $company->getId())
            ->getQuery();

        return $query->getResult();
    }

    function byConcat(string $concat): Location|null
    {
        try {
            return $this->createQueryBuilder("l")
                ->select("l")
                ->where("CONCAT(l.zipCode, ' ', l.city) = :concat")
                ->setParameter("concat", $concat)
                ->getQuery()
                ->getOneOrNullResult();
        } catch (Throwable) {
            return null;
        }
    }
}