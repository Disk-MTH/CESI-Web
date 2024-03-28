<?php

namespace stagify\Model\Repositories;

use Doctrine\ORM\EntityRepository;

final class InternshipRepo extends EntityRepository
{
    public function pagination(int $page, string|null $date, string|null $rating, string|array|null $skills, int $limit = 12): array
    {
        if ($skills != null) $skills = explode(",", $skills);

        //TODO: apply filters
        $query = $this->createQueryBuilder("io")
            ->select("io")
            ->orderBy("io.id", "ASC")
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit);


        return $query->getQuery()->getResult();
    }
}