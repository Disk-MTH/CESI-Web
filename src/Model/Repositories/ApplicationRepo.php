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
        /*try {

        } catch (Throwable) {
            return null;
        }*/

        $application = (new Application())
            ->setAccepted(false)
            ->setCvFile($data["cv"])
            ->setCoverLetterFile($data["coverLetter"])
            ->setUser($data["user"])
            ->setInternship($data["internship"]);

        $this->_em->persist($application);
        $this->_em->flush();

        return $application;
    }

    public function byInternshipId(int $internshipId): array
    {
        return $this->createQueryBuilder("a")
            ->select("a")
            ->innerJoin("a.user", "u")
            ->where("a.internship = :internshipId")
            ->setParameter("internshipId", $internshipId)
            ->getQuery()
            ->getArrayResult();
    }

    public function applicationByInternshipId(int $internshipId): array
    {
        return $this->createQueryBuilder("a")
            ->select("a")
            ->innerJoin("a.user", "u")
            ->where("a.internship = :internshipId")
            ->setParameter("internshipId", $internshipId)
            ->getQuery()
            ->getResult();
    }


    public function getUserbyApplicationId(int $applicationId): array
    {
        return $this->createQueryBuilder("a")
            ->select("u")
            ->innerJoin("a.user", "u")
            ->where("a.id = :applicationId")
            ->setParameter("applicationId", $applicationId)
            ->getQuery()
            ->getArrayResult();
    }
}