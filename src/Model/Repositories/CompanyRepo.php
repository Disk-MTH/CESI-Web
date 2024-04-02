<?php

namespace stagify\Model\Repositories;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use stagify\Model\Entities\Company;
use stagify\Model\Entities\Internship;
use Throwable;

final class CompanyRepo extends EntityRepository
{
    function pagination(int $page, string|null $rating, string|null $internshipsCount, string|null $internsCount, int|null $employeesCountLow, int|null $employeesCountHigh, bool $count, int $limit = 12): array|int
    {
        //TODO: apply filters
        $builder = $this->createQueryBuilder("c")
            ->select("c.id, c.name, l.city, l.zipCode, c.logoPath, cio.id, cio.title")
            ->addSelect("COUNT(ir.id) AS numberOfReviews")
            ->innerJoin("c.internships", "cio")
            ->innerJoin("cio.location", "l")
            ->leftJoin("cio.rates", "ir")
            ->groupBy("c.id, c.name, l.city, l.zipCode, c.logoPath, cio.title, cio.id");

        if ($count) {
            try {
                return array_sum(array_column($builder->select("COUNT(c.id)")->getQuery()->getScalarResult(), 1));
            } catch (Throwable) {
                return -1;
            }
        }

        return $builder
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    function byInternshipId(int $internshipId): Company|null
    {
        $query = $this->createQueryBuilder("c")
            ->select("c")
            ->innerJoin("c.internships", "i")
            ->where("i.id = :internshipId")
            ->setParameter("internshipId", $internshipId)
            ->getQuery();

        try {
            return $query->getSingleResult();
        } catch (Throwable) {
            return null;
        }
    }


}