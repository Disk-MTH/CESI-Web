<?php

namespace stagify\Model\Repositories;

use Doctrine\ORM\EntityRepository;
use stagify\Model\Entities\Location;
use stagify\Model\Entities\Promo;
use stagify\Model\Entities\Skill;
use stagify\Model\Entities\User;
use Throwable;

final class UserRepo extends EntityRepository
{
    function pagination(int $page, int|null $role, bool $count, int $limit = 12): array|int
    {
        if (!$role) $role = 3;

        //TODO: apply filters
        $builder = $this->createQueryBuilder("u")
            ->select("u.id, u.firstName, u.lastName, u.profilePicture, l.city, l.zipCode, p.year, p.type, p.school")
            ->innerJoin("u.location", "l")
            ->innerJoin("u.promos", "p")
            ->where("u.deleted = 0")
            ->andWhere("u.role = :role")
            ->setParameter("role", $role);

        if ($count) {
            try {
                return array_sum(array_column($builder->select("COUNT(u)")->getQuery()->getScalarResult(), 1));
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

    function isWish(int $internshipId): bool
    {
        $query = $this->createQueryBuilder("u")
            ->select("u.id")
            ->innerJoin("u.wishes", "w")
            ->where("u.deleted = 0")
            ->andWhere("w.id = :internshipId")
            ->setParameter("internshipId", $internshipId)
            ->getQuery();

        return count($query->getResult()) > 0;
    }

    function getPilots(int $page, int $limit = 12): array
    {
        $query = $this->createQueryBuilder("u")
            ->select("u.id, u.firstName, u.lastName, u.profilePicture, l.city, l.zipCode, p.year, p.type, p.school")
            ->innerJoin("u.location", "l")
            ->innerJoin("u.promos", "p")
            ->where("u.deleted = 0")
            ->andWhere("u.role = 2")
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit)
            ->getQuery();

        return $query->getResult();
    }

    public function create(array $data): User|null
    {
        try {
            $user = (new User())
                ->setRole($data["role"])
                ->setFirstName($data["firstName"])
                ->setLastName($data["lastName"])
                ->setProfilePicture($data["profilePicture"])
                ->setDescription($data["description"])
                ->setLogin($data["login"])
                ->setPasswordHash(hash("sha512", $data["password"]))
                ->setLocation($data["location"]);

            if ($data["role"] == 2) {
                $user->setPromos($data["promos"]);
            } else {
                $user->setSkills($data["skills"])
                    ->addPromo($data["promo"]);
            }

            $this->_em->persist($user);
            $this->_em->flush();

            return $user;
        } catch (Throwable) {
            return null;
        }
    }
}
