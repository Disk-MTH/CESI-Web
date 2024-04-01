<?php

namespace stagify\Controllers;

use Doctrine\ORM\EntityRepository;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use stagify\Model\Entities\Company;
use stagify\Model\Entities\Internship;
use stagify\Model\Entities\Location;
use stagify\Model\Entities\Skill;
use stagify\Model\Entities\User;
use stagify\Model\Repositories\CompanyRepo;
use stagify\Model\Repositories\InternshipRepo;
use stagify\Model\Repositories\LocationRepo;
use stagify\Model\Repositories\SkillRepo;
use stagify\Model\Repositories\UserRepo;

class ApiController extends Controller
{
    /** @var InternshipRepo $internshipRepo */
    private EntityRepository $internshipRepo;

    /** @var CompanyRepo $companyRepo */
    private EntityRepository $companyRepo;

    /** @var UserRepo $userRepo */
    private EntityRepository $userRepo;

    /** @var SkillRepo $internshipRepo */
    private EntityRepository $skillRepo;

    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
        $this->internshipRepo = $this->entityManager->getRepository(Internship::class);
        $this->companyRepo = $this->entityManager->getRepository(Company::class);
        $this->userRepo = $this->entityManager->getRepository(User::class);
        $this->skillRepo = $this->entityManager->getRepository(Skill::class);
    }

    function count(Request $request, Response $response, array $pathArgs): Response
    {
        $count = match ($pathArgs["type"]) {
            "internships" => $this->internshipRepo->pagination(
                -1,
                $request->getQueryParams()["date"] ?? null,
                $request->getQueryParams()["rating"] ?? null,
                $request->getQueryParams()["skills"] ?? null,
                true,
            ),
            "companies" => $this->companyRepo->pagination(
                -1,
                $request->getQueryParams()["rating"] ?? null,
                $request->getQueryParams()["internshipsCount"] ?? null,
                $request->getQueryParams()["internsCount"] ?? null,
                $request->getQueryParams()["employeesCountLow"] ?? null,
                $request->getQueryParams()["employeesCountHigh"] ?? null,
                true,
            ),
            "users" => $this->userRepo->pagination(
                -1,
                $request->getQueryParams()["role"] ?? null,
                true,
            ),
            default => -1,
        };

        return $this->json($response, ["count" => $count]);
    }

    function internships(Request $request, Response $response, array $pathArgs): Response
    {
        $queryArgs = $request->getQueryParams();
        $page = $pathArgs["page"];

        if ($page < 0) {
            $response->withStatus(404)->getBody()->write(json_encode(["error" => "Page out of range"]));
            return $response;
        }

        $this->logger->warning("Internships API called with parameters: " . json_encode($queryArgs));

        $internships = $this->internshipRepo->pagination(
            $page, $queryArgs["date"] ?? null,
            $queryArgs["rating"] ?? null,
            $queryArgs["skills"] ?? null,
            false,
        );
        $internships = array_map(function ($internship) {
            $company = $this->companyRepo->byInternship($internship);
            return [
                "title" => $internship->getTitle(),
                "salary" => $internship->getLowSalary() . " - " . $internship->getHighSalary(),
                "location" => $internship->getLocation()->getZipCode() . " - " . $internship->getLocation()->getCity(),
                "user_wish" => true,
                "company_name" => $company?->getName(),
                "company_logo" => $company?->getLogoPath(),
            ];
        }, $internships);

        return $this->json($response, $internships);
    }

    function companies(Request $request, Response $response, array $pathArgs): Response
    {
        $queryArgs = $request->getQueryParams();
        $page = $pathArgs["page"];

        if ($page < 0) {
            $response->withStatus(404)->getBody()->write(json_encode(["error" => "Page out of range"]));
            return $response;
        }

        $this->logger->warning("Companies API called with parameters: " . json_encode($queryArgs));

        $companies = $this->companyRepo->pagination(
            $page,
            $queryArgs["rating"] ?? null,
            $queryArgs["internshipsCount"] ?? null,
            $queryArgs["internsCount"] ?? null,
            $queryArgs["employeesCountLow"] ?? null,
            $queryArgs["employeesCountHigh"] ?? null,
            false,
        );
        $companies = array_map(function ($company) {
            return [
                "id" => $company["id"],
                "company" => $company["name"],
                "location" => $company["zipCode"] . " - " . $company["city"],
                "icon" => $company["logoPath"],
                "rating_count" => $company["numberOfReviews"],
            ];
        }, $companies);

        return $this->json($response, $companies);
    }

    function users(Request $request, Response $response, array $pathArgs): Response
    {
        $queryArgs = $request->getQueryParams();
        $page = $pathArgs["page"];

        if ($page < 0) {
            $response->withStatus(404)->getBody()->write(json_encode(["error" => "Page out of range"]));
            return $response;
        }

        $this->logger->warning("Users API called with parameters: " . json_encode($queryArgs));

        $users = $this->userRepo->pagination(
            $page,
            $request->getQueryParams()["role"] ?? null,
            false,
        );
        $users = array_map(function ($user) {
            return [
                "id" => $user["id"],
                "profile_picture" => $user["profilePicturePath"],
                "first_name" => $user["firstName"],
                "last_name" => $user["lastName"],
                "location" => $user["zipCode"] . " - " . $user["city"],
                "formation" => $user["school"],
            ];
        }, $users);

        return $this->json($response, $users);
    }

    //TODO
    function promos(Request $request, Response $response, array $pathArgs): Response
    {
        $skills = $this->skillRepo->suggestions($pathArgs["pattern"]);
        $skills = array_map(function ($skill) {
            return [
                "name" => $skill->getName(),
            ];
        }, $skills);

        return $this->json($response, $skills);
    }

    //TODO
    function campuses(Request $request, Response $response, array $pathArgs): Response
    {
        $skills = $this->skillRepo->suggestions($pathArgs["pattern"]);
        $skills = array_map(function ($skill) {
            return [
                "name" => $skill->getName(),
            ];
        }, $skills);

        return $this->json($response, $skills);
    }

    function skills(Request $request, Response $response, array $pathArgs): Response
    {
        $skills = $this->skillRepo->suggestions($pathArgs["pattern"]);
        $skills = array_map(function ($skill) {
            return [
                "name" => $skill->getName(),
            ];
        }, $skills);

        return $this->json($response, $skills);
    }
}