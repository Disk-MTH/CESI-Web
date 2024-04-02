<?php

namespace stagify\Model\Repositories;

use Doctrine\ORM\EntityRepository;
use stagify\Model\Entities\Promo;

final class PromoRepo extends EntityRepository
{
    function byCompany(Promo $promo): array
    {
        $query = $this->createQueryBuilder("p")
            ->select("p")
            ->join("p.companies", "c")
            ->where("c.id = :companyId")
            ->setParameter("companyId", $promo->getId())
            ->getQuery();

        return $query->getResult();
    }
}