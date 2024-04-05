<?php

namespace stagify\Model\Repositories;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;
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

    public function create(array $data): Rate|null
    {
        try {
            $rate = (new Rate())
                ->setDescription($data["description"])
                ->setGrade($data["job_rating"]);
            $data["user"]->addRate($rate);
            $data["internship"]->addRate($rate);
            $this->_em->persist($rate);
            $this->_em->flush();

            return $rate;
        } catch (Throwable) {
            return null;
        }
    }
}