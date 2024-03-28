<?php

namespace stagify\Model\Repositories;

use Doctrine\ORM\EntityRepository;
use stagify\Model\Entities\Company;
use stagify\Model\Entities\Internship;
use Throwable;

final class CompanyRepo extends EntityRepository
{
    function pagination(int $page, int $limit = 12): array
    {
        $query = $this->createQueryBuilder("c")
            ->select("c")
            ->orderBy("c.id", "ASC")
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit)
            ->getQuery();

        return $query->getResult();
    }

    function byInternship(Internship $internshipOffer): Company|null
    {
        $query = $this->createQueryBuilder('c')
            ->select('c')
            ->innerJoin('c.internshipOffers', 'io')
            ->where('io.id = :internshipOfferId')
            ->setParameter('internshipOfferId', $internshipOffer->getId())
            ->getQuery();

        try {
            return $query->getSingleResult();
        } catch (Throwable) {
            return null;
        }
    }

    //TODO: Rename this
    function getCompaniesDistinct(int $page, int $limit = 12): array
    {
        $query = $this->createQueryBuilder('c')
            ->select('DISTINCT c.id, c.name, l.city, l.zipCode,c.logoPath, cio.id, cio.title')
            ->addSelect('COALESCE(COUNT(ir.id), 0) AS numberOfReviews')
            ->innerJoin('c.internshipOffers', 'cio')
            ->innerJoin('cio.location', 'l')
            ->leftJoin('cio.rates', 'ir')
            ->groupBy('c.id, c.name, l.city, l.zipCode,c.logoPath ,cio.title, cio.id')
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit)
            ->getQuery();

        return $query->getResult();
    }
}