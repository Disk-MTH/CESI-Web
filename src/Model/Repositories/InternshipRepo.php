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
            ->select("i.id, i.title, i.lowSalary, i.highSalary, i.startDate, i.endDate, il.city, il.zipCode")
            ->innerJoin("i.location", "il");

        if ($date) $builder->orderBy("i.startDate", $date);

        if ($rating) $builder->addSelect("ROUND(AVG(r.grade) AS averageRating, 2)")
            ->innerJoin("i.rates", "r")
            ->groupBy("i.id")
            ->orderBy("averageRating", $rating);

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

    public function create(array $data): void
    {
        $internship = new Internship();
        $internship->setTitle($data["title"]);
        $internship->setDescription($data["description"]);
        $internship->setLowSalary($data["lowSalary"]);
        $internship->setHighSalary($data["highSalary"]);
        $internship->setStartDate($data["startDate"]);
        $internship->setEndDate($data["endDate"]);
        $internship->setLocation($this->_em->getReference(Location::class, $data["location"]));
        $internship->setCompany($this->_em->getReference(Company::class, $data["company"]));
        $internship->setSkills(array_map(fn($skill) => $this->_em->getReference(Skill::class, $skill), $data["skills"]));
        $this->_em->persist($internship);
        $this->_em->flush();
    }
}