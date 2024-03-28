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
use stagify\Model\Repositories\CompanyRepo;
use stagify\Model\Repositories\InternshipRepo;
use stagify\Model\Repositories\SkillRepo;

class ApiController extends Controller
{
    /** @var InternshipRepo $internshipRepo */
    private EntityRepository $internshipRepo;

    /** @var CompanyRepo $companyRepo */
    private EntityRepository $companyRepo;

    /** @var EntityRepository $locationRepo */
    private EntityRepository $locationRepo;

    /** @var SkillRepo $internshipRepo */
    private EntityRepository $skillRepo;

    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
        $this->internshipRepo = $this->entityManager->getRepository(Internship::class);
        $this->companyRepo = $this->entityManager->getRepository(Company::class);
        $this->locationRepo = $this->entityManager->getRepository(Location::class);
        $this->skillRepo = $this->entityManager->getRepository(Skill::class);
    }

    function internships(Request $request, Response $response, array $pathArgs): Response
    {
        $queryArgs = $request->getQueryParams();
        $page = $pathArgs["page"];

        if ($page < 0) {
            $response->withStatus(404)->getBody()->write(json_encode(["error" => "Page out of range"]));
            return $response;
        }

        $this->logger->debug("Query args: " . json_encode($queryArgs));
        $internships = $this->internshipRepo->pagination($page, $queryArgs["date"] ?? null, $queryArgs["rating"] ?? null, $queryArgs["skills"] ?? null);
        $internships = array_map(function ($internship) {
            $company = $this->companyRepo->byInternship($internship);
            return $company != null ? [
                "title" => $internship->getTitle(),
                "salary" => $internship->getLowSalary() . " - " . $internship->getHighSalary(),
                "location" => $internship->getLocation()->getZipCode() . " - " . $internship->getLocation()->getCity(),
                "user_wish" => true,
                "company_name" => $company->getName(),
                "company_logo" => $company->getLogoPath(),
            ] : [];
        }, $internships);

        return $this->json($response, $internships);
    }

    function companies(Request $request, Response $response, array $pathArgs): Response
    {
        $page = $pathArgs["page"];

        if ($page < 0) {
            $response->withStatus(404)->getBody()->write(json_encode(["error" => "Page out of range"]));
            return $response;
        }

        $companies = $this->companyRepo->getCompaniesDistinct($page);
        $companies = array_map(function ($company) {
            $location = $this->locationRepo->findByCompany($company); //TODO: Fix this
            return [
                "name" => $company->getName(),
                "logo" => $company->getLogoPath(),
                "location" => $location->getZipCode() . " - " . $location->getCity(),
            ];
        }, $companies);

        return $this->json($response, $companies);
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