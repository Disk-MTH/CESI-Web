<?php

namespace stagify\Model\Repositories;

use Doctrine\ORM\EntityRepository;
use stagify\Model\Entities\ActivitySector;
use Throwable;

class ActivitySectorRepo extends EntityRepository
{
    function suggestions(string $pattern, $limit = 5): array
    {
        return $this->createQueryBuilder("a")
            ->where("a.name LIKE :pattern")
            ->setParameter("pattern", "%" . $pattern . "%")
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    function byName(string $name): ActivitySector|null
    {
        try {
            return $this->createQueryBuilder("a")
                ->where("a.name = :name")
                ->setParameter("name", $name)
                ->getQuery()
                ->getOneOrNullResult();
        } catch (Throwable) {
            return null;
        }
    }

    function create(array $data): ActivitySector|null
    {
        try {
            $activitySector = (new ActivitySector())
                ->setName($data["name"]);
            $this->_em->persist($activitySector);
            $this->_em->flush();
            return $activitySector;
        } catch (Throwable) {
            return null;
        }
    }
}