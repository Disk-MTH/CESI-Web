<?php

namespace stagify\Model\Repositories;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use stagify\Model\Entities\Company;
use stagify\Model\Entities\InternshipOffer;

final class CompanyRepo extends EntityRepository
{
    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    function findByInternshipOffer(InternshipOffer $internshipOffer): Company
    {
        $query = $this->createQueryBuilder('c')
            ->select('c')
            ->innerJoin('c.internshipOffers', 'io')
            ->where('io.id = :internshipOfferId')
            ->setParameter('internshipOfferId', $internshipOffer->getId())
            ->getQuery();

        return $query->getSingleResult();
    }
}