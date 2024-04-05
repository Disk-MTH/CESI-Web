<?php

namespace stagify\Model\Repositories;

use Doctrine\ORM\EntityRepository;
use stagify\Model\Entities\Application;
use Throwable;

class ApplicationRepo extends EntityRepository
{
    function byUserId(int $userId): array
    {
        return $this->createQueryBuilder("a")
            ->select("a.accepted, i.id AS internshipId, i.title AS internshipTitle")
            ->innerJoin("a.internship", "i")
            ->innerJoin("i.location", "l")
            ->where("a.user = :userId")
            ->setParameter("userId", $userId)
            ->getQuery()
            ->getArrayResult();
    }

    public function create(array $data): Application|null
    {
        try {
            $application = (new Application())
                ->setCvFile($data["cv_file"])
                ->setCoverLetterFile($data["cover_letter"])
                ->setUser($data["user"])
                ->setInternship($data["internship"]);
            $this->_em->persist($application);
            $this->_em->flush();

            return $application;
        } catch (Throwable) {
            return null;
        }
    }
}