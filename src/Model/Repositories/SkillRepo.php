<?php

namespace stagify\Model\Repositories;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use stagify\Model\Entities\Company;
use stagify\Model\Entities\Internship;
use stagify\Model\Entities\Location;
use stagify\Model\Entities\Skill;
use Throwable;

final class SkillRepo extends EntityRepository
{
    function suggestions(string $pattern, $limit = 5): array
    {
        return $this->createQueryBuilder("s")
            ->where("s.name LIKE :pattern")
            ->setParameter("pattern", "%" . $pattern . "%")
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    function byName(string $name): Skill|null
    {
        try {
            return $this->createQueryBuilder("s")
                ->where("s.name = :name")
                ->setParameter("name", $name)
                ->getQuery()
                ->getOneOrNullResult();
        } catch (Throwable) {
            return null;
        }
    }

    function create(string $name): Skill|null
    {
        try {
            $skill = (new Skill())->setName($name);
            $this->_em->persist($skill);
            $this->_em->flush();

            return $skill;
        } catch (Throwable) {
            return null;
        }
    }
}