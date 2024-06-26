<?php

namespace stagify\Controllers;

use Doctrine\ORM\EntityRepository;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use stagify\Model\Entities\ActivitySector;
use stagify\Model\Entities\Company;
use stagify\Model\Entities\Internship;
use stagify\Model\Entities\Location;
use stagify\Model\Entities\Promo;
use stagify\Model\Entities\Skill;
use stagify\Model\Entities\User;
use stagify\Model\Repositories\ActivitySectorRepo;
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

    /** @var ActivitySectorRepo $activitySectorRepo */
    private EntityRepository $activitySectorRepo;

    /** @var LocationRepo $locationRepo */
    private EntityRepository $locationRepo;

    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
        $this->internshipRepo = $this->entityManager->getRepository(Internship::class);
        $this->companyRepo = $this->entityManager->getRepository(Company::class);
        $this->userRepo = $this->entityManager->getRepository(User::class);
        $this->skillRepo = $this->entityManager->getRepository(Skill::class);
        $this->promoRepo = $this->entityManager->getRepository(Promo::class);
        $this->activitySectorRepo = $this->entityManager->getRepository(ActivitySector::class);
        $this->locationRepo = $this->entityManager->getRepository(Location::class);
    }

    function count(Request $request, Response $response, array $pathArgs): Response
    {
        $count = match ($pathArgs["type"]) {
            "internships" => $this->internshipRepo->pagination(
                -1,
                $request->getQueryParams()["date"] ?? null,
                $request->getQueryParams()["rating"] ?? null,
                $request->getQueryParams()["skills"] ?? null,
                $request->getQueryParams()["keyword"] ?? null,
                $request->getQueryParams()["location"] ?? null,
                true,
            ),
            "companies" => $this->companyRepo->pagination(
                -1,
                $request->getQueryParams()["rating"] ?? null,
                $request->getQueryParams()["internshipsCount"] ?? null,
                $request->getQueryParams()["employeesCount"] ?? null,
                $request->getQueryParams()["keyword"] ?? null,
                $request->getQueryParams()["location"] ?? null,
                true,
            ),
            "users" => $this->userRepo->pagination(
                -1,
                $request->getQueryParams()["role"] ?? null,
                $request->getQueryParams()["keyword"] ?? null,
                $request->getQueryParams()["location"] ?? null,
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

        $internships = $this->internshipRepo->pagination(
            $page, $queryArgs["date"] ?? null,
            $queryArgs["rating"] ?? null,
            $queryArgs["skills"] ?? null,
            $queryArgs["keyword"] ?? null,
            $queryArgs["location"] ?? null,
            false,
        );
        $internships = array_map(function ($internship) {
            $company = $this->companyRepo->byInternshipId($internship["id"]);

            return [
                "id" => $internship["id"],
                "url" => "/internship/" . $internship["id"],
                "startDate" => $internship["startDate"]->format("d/m/Y"),
                "endDate" => $internship["endDate"]->format("d/m/Y"),
                "rate" => round((float)$internship["rate"]),
                "title" => $internship["title"],
                "salary" => $internship["lowSalary"] . " - " . $internship["highSalary"],
                "location" => $internship["zipCode"] . " - " . $internship["city"],
                "userWish" => $this->userRepo->isWish($internship["id"]),
                "companyName" => $company->getName(),
                "companyLogo" => "/files/companies/" . $company->getLogoPicture(),
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

        $companies = $this->companyRepo->pagination(
            $page,
            $queryArgs["rating"] ?? null,
            $queryArgs["internshipsCount"] ?? null,
            $queryArgs["employeesCount"] ?? null,
            $queryArgs["keyword"] ?? null,
            $queryArgs["location"] ?? null,
            false,
        );
        $companies = array_map(function ($company) {
            return [
                "company" => $company["name"],
                "location" => $company["zipCode"] . " - " . $company["city"],
                "internshipsCount" => $company["numberOfInternships"],
                "employeesCount" => $company["employeeCount"],
                "logo" => "/files/companies/" . $company["logoPicture"],
                "ratingsCount" => $company["numberOfReviews"],
                "rate" => round((float)$company["averageGrade"]),
                "url" => "/company/" . $company["id"] . "/" . $company["locationId"],
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

        $users = $this->userRepo->pagination(
            $page,
            $request->getQueryParams()["role"] ?? null,
            $queryArgs["keyword"] ?? null,
            $queryArgs["location"] ?? null,
            false,
        );
        $users = array_map(function ($user) {
            return [
//                "id" => $user["id"],
                "url" => "/user/" . $user["id"],
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

    function activitySectorsSuggestions(Request $request, Response $response, array $pathArgs): Response
    {
        $sectors = $this->activitySectorRepo->suggestions($pathArgs["pattern"]);
        $sectors = array_map(fn($sector) => ["content" => $sector->getName()], $sectors);
        return $this->json($response, $sectors);
    }

    function zipCodesSuggestions(Request $request, Response $response, array $pathArgs): Response
    {
        $zipCodes = $this->locationRepo->suggestions($pathArgs["pattern"], false);
        $zipCodes = array_map(fn($zipCode) => ["content" => $zipCode->getZipCode()], $zipCodes);
        return $this->json($response, $zipCodes);
    }

    function citiesSuggestions(Request $request, Response $response, array $pathArgs): Response
    {
        $cities = $this->locationRepo->suggestions($pathArgs["pattern"], true);
        $cities = array_map(fn($city) => ["content" => $city->getCity()], $cities);
        return $this->json($response, $cities);
    }

    function toggleWish(Request $request, Response $response, array $pathArgs): Response
    {
        if ($this->userRepo->toggleWish($this->internshipRepo->find($pathArgs["id"]))) {
            return $this->json($response, ["success" => true]);
        }
        return $this->json($response, ["success" => false]);
    }
}