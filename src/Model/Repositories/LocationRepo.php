<?php

namespace stagify\Model\Repositories;

use Doctrine\ORM\EntityRepository;
use stagify\Model\Entities\Company;
use stagify\Model\Entities\Location;
use Throwable;

final class LocationRepo extends EntityRepository
{

    function suggestions(string $pattern, bool $city, $limit = 5): array
    {
        $query = $this->createQueryBuilder("l")
            ->select("l");

        if ($city) $query->where("l.city LIKE :pattern");
        else $query->where("l.zipCode LIKE :pattern");

        return $query
            ->setParameter("pattern", "%" . $pattern . "%")
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

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

    function byConcat(string $concat): Location|null
    {
        try {
            return $this->createQueryBuilder("l")
                ->select("l")
                ->where("CONCAT(l.zipCode, ' ', l.city) = :concat")
                ->setParameter("concat", $concat)
                ->getQuery()
                ->getOneOrNullResult();
        } catch (Throwable) {
            return null;
        }
    }

    function byData(int $zipCode, string $city): Location|null
    {
        try {
            return $this->createQueryBuilder("l")
                ->select("l")
                ->where("l.zipCode = :zipCode")
                ->andWhere("l.city = :city")
                ->setParameter("zipCode", $zipCode)
                ->setParameter("city", $city)
                ->getQuery()
                ->getOneOrNullResult();
        } catch (Throwable) {
            return null;
        }
    }

    function create(array $data): Location|null
    {
        try {
            $location = (new Location())
                ->setZipCode($data["zipCode"])
                ->setCity($data["city"]);
            $this->_em->persist($location);
            $this->_em->flush();

            return $location;
        } catch (Throwable) {
            return null;
        }
    }
}