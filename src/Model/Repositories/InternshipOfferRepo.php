<?php

namespace stagify\Model\Repositories;

use Doctrine\ORM\EntityRepository;

final class InternshipOfferRepo extends EntityRepository
{
    //add a method to get all internship offers with pagination
    public function getInternshipOffer(int $page, int $limit, int $tile)
    {
        $query = $this->createQueryBuilder('io')
            ->select('io')
            ->orderBy('io.id', 'ASC')
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit)
            ->getQuery();

        return $query->getResult()[$tile];
    }
}
