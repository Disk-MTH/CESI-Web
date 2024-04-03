<?php

namespace stagify\Model\Repositories;

use Doctrine\ORM\EntityRepository;
use stagify\Model\Entities\Promo;
use Throwable;

final class PromoRepo extends EntityRepository
{
    function suggestions(string $pattern, $limit = 5): array
    {
        return $this->createQueryBuilder("p")
            ->where("CONCAT('A', p.year) LIKE :pattern")
            ->orWhere("p.type LIKE :pattern")
            ->orWhere("p.school LIKE :pattern")
            ->setParameter("pattern", "%" . $pattern . "%")
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    function byConcat(string $concat): Promo|null
    {
        try {
            return $this->createQueryBuilder("p")
                ->select("p")
                ->where("CONCAT('A', p.year, ' ', p.type, ' - ', p.school) = :concat")
                ->setParameter("concat", $concat)
                ->getQuery()
                ->getOneOrNullResult();
        } catch (Throwable) {
            return null;
        }
    }

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