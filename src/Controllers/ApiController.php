<?php

namespace stagify\Controllers;

use Doctrine\ORM\EntityRepository;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use stagify\Model\Entities\Company;
use stagify\Model\Entities\Internship;
use stagify\Model\Entities\Location;
use stagify\Model\Entities\Promo;
use stagify\Model\Entities\Skill;
use stagify\Model\Entities\User;
use stagify\Model\Repositories\CompanyRepo;
use stagify\Model\Repositories\InternshipRepo;
use stagify\Model\Repositories\LocationRepo;
use stagify\Model\Repositories\SkillRepo;
use stagify\Model\Repositories\UserRepo;
use stagify\Model\Repositories\PromoRepo;

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

    /** @var PromoRepo $promoRepo */
    private EntityRepository $promoRepo;

    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
        $this->internshipRepo = $this->entityManager->getRepository(Internship::class);
        $this->companyRepo = $this->entityManager->getRepository(Company::class);
        $this->userRepo = $this->entityManager->getRepository(User::class);
        $this->skillRepo = $this->entityManager->getRepository(Skill::class);
        $this->promoRepo = $this->entityManager->getRepository(Promo::class);
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
                $request->getQueryParams()["employeesCount"] ?? null,
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
            $company = $this->companyRepo->byInternshipId($internship["id"]);
            return [
                "url" => "/internship/" . $internship["id"],
                "startDate" => $internship["startDate"]->format("d/m/Y"),
                "endDate" => $internship["endDate"]->format("d/m/Y"),
                "rate" => round((float)$internship["rate"]),
                "title" => $internship["title"],
                "salary" => $internship["lowSalary"] . " - " . $internship["highSalary"],
                "location" => $internship["zipCode"] . " - " . $internship["city"],
                "user_wish" => $this->userRepo->isWish($internship["id"]),
                "company_name" => $company->getName(),
                "company_logo" => $company->getLogoPath(),
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
            $queryArgs["employeesCount"] ?? null,
            false,
        );
        $companies = array_map(function ($company) {
            return [
                "company" => $company["name"],
                "location" => $company["zipCode"] . " - " . $company["city"],
                "internshipsCount" => $company["numberOfInternships"],
                "employeesCount" => $company["employeeCount"],
                "icon" => $company["logoPath"],
                "ratingsCount" => $company["numberOfReviews"],
                "rate" => round((float)$company["averageGrade"]),
                "url" => "/company/" . $company["id"],
                "ratingUrl" => "/company/" . $company["id"] . "/rating",

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
                "profilePicture" => "/files/users/" . $user["profilePicture"],
                "firstName" => $user["firstName"],
                "lastName" => $user["lastName"],
                "location" => $user["zipCode"] . " - " . $user["city"],
                "formation" => $user["school"],
            ];
        }, $users);

        return $this->json($response, $users);
    }

    function companiesSuggestions(Request $request, Response $response, array $pathArgs): Response
    {
        $companies = $this->companyRepo->suggestions($pathArgs["pattern"]);
        $companies = array_map(fn($company) => ["content" => $company["name"] . " - " . $company["zipCode"] . " " . $company["city"]], $companies);
        return $this->json($response, $companies);
    }

    function promosSuggestions(Request $request, Response $response, array $pathArgs): Response
    {
        $promos = $this->promoRepo->suggestions($pathArgs["pattern"]);
        $promos = array_map(fn($promo) => ["content" => "A" . $promo->getYear() . " " . $promo->getType() . " - " . $promo->getSchool()], $promos);
        return $this->json($response, $promos);
    }

    function skillsSuggestions(Request $request, Response $response, array $pathArgs): Response
    {
        $skills = $this->skillRepo->suggestions($pathArgs["pattern"]);
        $skills = array_map(fn($skill) => ["content" => $skill->getName()], $skills);
        return $this->json($response, $skills);
    }
}