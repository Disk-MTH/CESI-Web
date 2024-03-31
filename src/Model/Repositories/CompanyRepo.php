<?php

namespace stagify\Model\Repositories;

use Doctrine\ORM\EntityRepository;
use stagify\Model\Entities\Company;
use stagify\Model\Entities\Internship;
use Throwable;

final class CompanyRepo extends EntityRepository
{
    function pagination(int $page, string|null $rating, string|null $internshipsCount, string|null $internsCount, int|null $employeesCountLow, int|null $employeesCountHigh, int $limit = 12): array
    {
        //TODO: apply filters
        $query = $this->createQueryBuilder("c")
            ->select("DISTINCT c.id, c.name, l.city, l.zipCode,c.logoPath, cio.id, cio.title")
            ->addSelect("COUNT(ir.id) AS numberOfReviews")
            ->innerJoin("c.internships", "cio")
            ->innerJoin("cio.location", "l")
            ->leftJoin("cio.rates", "ir")
            ->groupBy("c.id, c.name, l.city, l.zipCode, c.logoPath, cio.title, cio.id")
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit)
            ->getQuery();

        return $query->getResult();
    }

    function byInternship(Internship $internship): Company|null
    {
        $query = $this->createQueryBuilder("c")
            ->select("c")
            ->innerJoin("c.internships", "i")
            ->where("i.id = :internshipId")
            ->setParameter("internshipId", $internship->getId())
            ->getQuery();

        try {
            return $query->getSingleResult();
        } catch (Throwable) {
            return null;
        }
    }
}