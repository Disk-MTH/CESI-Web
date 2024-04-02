<?php

namespace stagify\Model\Repositories;

use Doctrine\ORM\EntityRepository;
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
}