<?php

namespace stagify\Model\Repositories;

use Doctrine\ORM\EntityRepository;

final class InternshipOfferRepo extends EntityRepository
{
    public function getInternshipOffers(int $page, int $limit): array
    {
        $query = $this->createQueryBuilder('io')
            ->select('io')
            ->orderBy('io.id', 'ASC')
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit)
            ->getQuery();

        return $query->getResult();
    }
}
