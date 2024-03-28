<?php

namespace stagify\Model\Repositories;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use stagify\Model\Entities\Company;
use stagify\Model\Entities\Internship;

final class SkillRepo extends EntityRepository
{
    function findSuggestions(string $pattern, $limit = 5): array
    {
        $query = $this->createQueryBuilder("s")
            ->where("s.name LIKE :pattern")
            ->setParameter("pattern", $pattern . "%")
            ->setMaxResults($limit)
            ->getQuery();

        return $query->getResult();
    }
}