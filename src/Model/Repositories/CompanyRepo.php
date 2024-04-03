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
    function pagination(int $page, string|null $rating, string|null $internshipsCount, string|null $internsCount, string|null $employeesCount, bool $count, int $limit = 12): array|int
    {
        //TODO: apply filters
        /*$builder = $this
            ->createQueryBuilder("c")
            ->select("c.id, c.name, l.city, l.zipCode, c.logoPath, cio.id, cio.title, c.employeeCount")
            ->addSelect("COUNT(ir.id) AS numberOfReviews")
            ->addSelect("AVG(ir.grade) AS averageRating")
            ->innerJoin("c.internships", "cio")
            ->innerJoin("cio.location", "l")
            ->leftJoin("cio.rates", "ir")
            ->groupBy("c.id, c.name, l.city, l.zipCode, c.logoPath, cio.title, cio.id");*/

        $builder = $this->createQueryBuilder("c")
            ->select("c.id, c.name, l.zipCode, l.city, c.employeeCount, c.logoPicture")
            ->addSelect("(SELECT COUNT(io.id) FROM stagify\Model\Entities\Company c1 JOIN c1.internships io WHERE c1.id = c.id) AS numberOfInternships")
            ->addSelect("(SELECT COUNT(r1.id) FROM stagify\Model\Entities\Company c2 JOIN c2.internships io1 JOIN io1.rates r1 WHERE c2.id = c.id) AS numberOfReviews")
            ->addSelect("(SELECT AVG(r2.grade) FROM stagify\Model\Entities\Company c3 JOIN c3.internships io2 JOIN io2.rates r2 WHERE c3.id = c.id) AS averageGrade")
            ->innerJoin("c.locations", "l");


        if ($rating) $builder->orderBy("averageGrade", $rating);

        if ($internshipsCount) $builder->orderBy("numberOfInternships", $internshipsCount);

        if ($employeesCount) {
            $limits = explode("_", $employeesCount);
            if ($limits[0] != 0) $builder
                ->andWhere("c.employeeCount >= :minEmployees")
                ->setParameter("minEmployees", $limits[0]);
            if ($limits[1] != 0) $builder
                ->andWhere("c.employeeCount <= :maxEmployees")
                ->setParameter("maxEmployees", $limits[1]);
        }


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

    function suggestions(string $pattern, $limit = 5): array
    {
        return $this->createQueryBuilder("c")
            ->where("c.name LIKE :pattern")
            ->orWhere("l.city LIKE :pattern")
            ->orWhere("l.zipCode LIKE :pattern")
            ->setParameter("pattern", "%" . $pattern . "%")
            ->select("c.name, l.city, l.zipCode")
            ->innerJoin("c.locations", "l")
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    function byConcat(string $concat): Company|null
    {
        try {
            return $this->createQueryBuilder("c")
                ->select("c")
                ->innerJoin("c.locations", "l")
                ->where("CONCAT(c.name, ' - ', l.zipCode, ' ', l.city) = :concat")
                ->setParameter("concat", $concat)
                ->getQuery()
                ->getOneOrNullResult();
        } catch (Throwable) {
            return null;
        }
    }

    function byInternshipId(int $internshipId): Company|null
    {
        try {
            return $this->createQueryBuilder("c")
                ->select("c")
                ->innerJoin("c.internships", "i")
                ->where("i.id = :internshipId")
                ->setParameter("internshipId", $internshipId)
                ->getQuery()->getSingleResult();
        } catch (Throwable) {
            return null;
        }
    }

    function create(array $data): Company|null
    {
        try {
            $company = (new Company())
                ->setName($data["companyName"])
                ->setWebsite($data["website"])
                ->setEmployeeCount($data["employeeCount"])
                ->setLogoPicture($data["logoPicture"])
                ->setActivitySector($data["sector"])
                ->setLocations($data["locations"]);
            $this->_em->persist($company);
            $this->_em->flush();
            return $company;
        } catch (Throwable) {
            return null;
        }
    }

    function update(array $data): Company|null
    {
        try {
            $company = $this->find($data["id"]);
            if ($company) {
                $company
                    ->setName($data["companyName"])
                    ->setWebsite($data["website"])
                    ->setEmployeeCount($data["employeeCount"])
                    ->setLogoPicture($data["logoPicture"])
                    ->setActivitySector($data["sector"])
                    ->setLocations($data["locations"]);
                $this->_em->flush();
                return $company;
            }
            return null;
        } catch (Throwable) {
            return null;
        }
    }

    public function delete(int $id): bool
    {
        try {
            $company = $this->find($id);
            if ($company) {
                $company->setDeleted(true);
                $this->_em->flush();
                return true;
            }
            return false;
        } catch (Throwable) {
            return false;
        }
    }

    public function restore(int $id): bool
    {
        try {
            $company = $this->find($id);
            if ($company) {
                $company->setDeleted(false);
                $this->_em->flush();
                return true;
            }
            return false;
        } catch (Throwable) {
            return false;
        }
    }
}