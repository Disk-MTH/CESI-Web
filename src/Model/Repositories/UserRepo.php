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
            ->select("u.id, u.firstName, u.lastName, u.profilePicturePath, l.city, l.zipCode, p.year, p.type, p.school")
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
            ->select("u.id, u.firstName, u.lastName, u.profilePicturePath, l.city, l.zipCode, p.year, p.type, p.school")
            ->innerJoin("u.location", "l")
            ->innerJoin("u.promos", "p")
            ->where("u.deleted = 0")
            ->andWhere("u.role = 2")
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit)
            ->getQuery();

        return $query->getResult();
    }

    public function create(array $data): bool
    {
        try {
            $user = (new User())
                ->setLastName($data["lastName"])
                ->setFirstName($data["firstName"])
                ->setLocation($this->_em->getReference(Location::class, $data["location"]))
                ->setLogin($data["username"])
                ->setPasswordHash(password_hash($data["password"], PASSWORD_DEFAULT))
                ->setRole($data["role"])
                ->setProfilePicturePath($data["photoPath"])
                ->setDescription($data["description"]);

            if ($data["role"] === 3) {
                $user->addSkill($this->_em->getReference(Skill::class, $data["skills"]))
                    ->addPromo($this->_em->getReference(Promo::class, $data["promo"]));
            }
            if ($data["role"] === 2) {
                $user->addPromo($this->_em->getReference(Promo::class, $data["promos"]));
            }

            $this->_em->persist($user);
            $this->_em->flush();

            return true;
        } catch (Throwable) {
            return false;
        }

    }
}