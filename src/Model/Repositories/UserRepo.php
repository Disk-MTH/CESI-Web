<?php

namespace stagify\Model\Repositories;

use Doctrine\ORM\EntityRepository;
use stagify\Model\Entities\Internship;
use stagify\Model\Entities\Location;
use stagify\Model\Entities\Promo;
use stagify\Model\Entities\Rate;
use stagify\Model\Entities\Skill;
use stagify\Model\Entities\User;
use Throwable;

final class UserRepo extends EntityRepository
{
    function pagination(int $page, int|null $role, string|null $keyword, string|null $location, bool $count, int $limit = 12): array|int
    {
        if (!$role) $role = 3;

        $builder = $this->createQueryBuilder("u")
            ->select("u.id, u.firstName, u.lastName, u.profilePicture, l.city, l.zipCode, p.year, p.type, p.school")
            ->innerJoin("u.location", "l")
            ->innerJoin("u.promos", "p")
            ->where("u.deleted = 0")
            ->andWhere("u.role = :role")
            ->setParameter("role", $role);

        if ($keyword) {
            $builder->andWhere("u.firstName LIKE :keyword OR u.lastName LIKE :keyword OR p.school LIKE :keyword")
                ->setParameter("keyword", "%$keyword%");
        }

        if ($location) {
            $builder->andWhere("l.city LIKE :location OR l.zipCode LIKE :location")
                ->setParameter("location", "%$location%");
        }

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

    public function isWish(int $internshipId): bool
    {
        return !empty($this->createQueryBuilder("u")
            ->select("w.id")
            ->innerJoin("u.wishes", 'w')
            ->where("u.id = :userId")
            ->andWhere("w.id = :internshipId")
            ->setParameter("userId", $_SESSION["user"])
            ->setParameter("internshipId", $internshipId)
            ->getQuery()
            ->getResult()
        );
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

    public function findByRate(Rate $rate): User|null
    {
        try {
            return $this->createQueryBuilder("u")
                ->select("u")
                ->innerJoin("u.rates", "r")
                ->where("r.id = :rateId")
                ->setParameter("rateId", $rate->getId())
                ->getQuery()
                ->getOneOrNullResult();
        } catch (Throwable) {
            return null;
        }


    }

    public function toggleWish(Internship|null $internship): bool
    {
        $user = $this->find($_SESSION["user"]);
        try {
            if ($user->getWishes()->contains($internship)) {
                $user->removeWish($internship);
            } else {
                $user->addWish($internship);
            }

            $this->_em->flush();
            return true;
        } catch (Throwable) {
            return false;
        }
    }
}
