<?php

namespace stagify\Model\Repositories;

use Doctrine\ORM\EntityRepository;

final class InternshipRepo extends EntityRepository
{
    public function getInternships(int $page, string | null $date, string | null $rating, array $skills, int $limit = 12): array
    {
        //apply filters if any
        $query = $this->createQueryBuilder("io")
            ->select("io")
            ->orderBy("io.id", "ASC")
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit);

/*        if ($date) {
            $query->andWhere("io.startDate <= :date")
                ->setParameter("date", $date);
        }

        if ($rating) {
            $query->andWhere("io.rating >= :rating")
                ->setParameter("rating", $rating);
        }

        if ($skills) {
            $query->innerJoin("io.skills", "s")
                ->andWhere("s.id IN (:skills)")
                ->setParameter("skills", $skills);
        }*/

        return $query->getQuery()->getResult();
    }
}