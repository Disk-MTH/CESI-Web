<?php

namespace stagify\Model\Repositories;

use Doctrine\ORM\EntityRepository;
use stagify\Model\Entities\Company;

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
}