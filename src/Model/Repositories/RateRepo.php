<?php

namespace stagify\Model\Repositories;

use Doctrine\ORM\EntityRepository;
use stagify\Model\Entities\ActivitySector;
use stagify\Model\Entities\Internship;
use stagify\Model\Entities\Rate;
use Throwable;

class RateRepo extends EntityRepository
{
    function suggestions(string $pattern, $limit = 5): array
    {
        return $this->createQueryBuilder("r")
            ->where("r.name LIKE :pattern")
            ->setParameter("pattern", "%" . $pattern . "%")
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    function byName(string $name): ActivitySector|null
    {
        try {
            return $this->createQueryBuilder("r")
                ->where("r.name = :name")
                ->setParameter("name", $name)
                ->getQuery()
                ->getOneOrNullResult();
        } catch (Throwable) {
            return null;
        }
    }

    public function create(array $data): Rate|null
    {
        try {
            $rate = (new Rate())
                ->setDescription($data["description"])
                ->setGrade($data["grade"]);
            $data["user"]->addRate($rate);
            $data["company"]->addRate($rate);
            $this->_em->persist($rate);
            $this->_em->flush();

            return $rate;
        } catch (Throwable) {
            return null;
        }
    }


}