<?php

namespace stagify\Model\Repositories;

use Doctrine\ORM\EntityRepository;
use Throwable;

final class InternshipRepo extends EntityRepository
{
    public function pagination(int $page, string|null $date, string|null $rating, string|array|null $skills, bool $count, int $limit = 12): array|int
    {
        if ($skills != null) $skills = explode(",", $skills);

        //TODO: apply filters
        $builder = $this->createQueryBuilder("io")
            ->select("io")
            ->orderBy("io.id", "ASC");

        if ($count) {
            try {
                return array_sum(array_column($builder->select("COUNT(io)")->getQuery()->getScalarResult(), 1));
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