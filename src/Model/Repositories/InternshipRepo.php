<?php

namespace stagify\Model\Repositories;

use Doctrine\ORM\EntityRepository;
use stagify\Model\Entities\Company;
use stagify\Model\Entities\Internship;
use stagify\Model\Entities\Location;
use stagify\Model\Entities\Skill;
use Throwable;

final class InternshipRepo extends EntityRepository
{
    public function pagination(int $page, string|null $date, string|null $rating, string|array|null $skills, bool $count, int $limit = 12): array|int
    {
        $builder = $this->createQueryBuilder("i")
            ->select("i.id, i.title, i.lowSalary, i.highSalary, i.startDate, i.endDate, il.city, il.zipCode, AVG(r.grade) AS rate")
            ->innerJoin("i.location", "il")
            ->leftJoin("i.rates", "r")
            ->groupBy("i.id");

        if ($date) $builder->orderBy("i.startDate", $date);

        if ($rating) $builder->orderBy("rate", $rating);

        if ($skills) {
            $skills = explode(",", $skills);
            if (count($skills) > 0) {
                $builder->innerJoin("i.skills", "s")
                    ->where("s.name IN (:skills)")
                    ->setParameter("skills", $skills)
                    ->groupBy("i.id");
            }
        }

        if ($count) {
            try {
                return array_sum(array_column($builder->select("COUNT(i.id)")->getQuery()->getScalarResult(), 1));
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

    //TODO: redo this method
    public function create(array $data): bool
    {
        try {
            $this->_em->persist((new Internship())
                ->setTitle($data["title"])
                ->setDescription($data["description"])
                ->setLowSalary($data["lowSalary"])
                ->setHighSalary($data["highSalary"])
                ->setStartDate($data["startDate"])
                ->setEndDate($data["endDate"])
                ->setLocation($this->_em->getReference(Location::class, $data["location"]))
                ->setCompany($this->_em->getReference(Company::class, $data["company"]))
                ->setSkills(array_map(fn($skill) => $this->_em->getReference(Skill::class, $skill), $data["skills"]))
            );
            $this->_em->flush();

            return true;
        } catch (Throwable) {
            return false;
        }
    }
}