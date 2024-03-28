<?php

use Doctrine\ORM\EntityManager;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Log\LoggerInterface as Logger;
use Slim\App;
use Slim\Views\Twig;
use stagify\Model\Entities\Company;
use stagify\Model\Entities\Internship;
use stagify\Model\Entities\Location;
use stagify\Model\Entities\Skill;
use stagify\Model\Repositories\CompanyRepo;
use stagify\Model\Repositories\InternshipRepo;
use stagify\Model\Repositories\SkillRepo;

return function (App $app, Logger $logger, Twig $twig, EntityManager $entityManager) {
    $app->get("/internships/{page}", function (Request $request, Response $response, array $args) use ($entityManager, $logger) {
        $queryArgs = $request->getQueryParams();

        $page = $args["page"];
        $date = $queryArgs["date"] ?? null;
        $rating = $queryArgs["rating"] ?? null;
        $skills = $queryArgs["skills"] ?? [];

        if ($page < 0) {
            $response->withStatus(404)->getBody()->write(json_encode(["error" => "Page out of range"]));
            return $response;
        }

        /** @var InternshipRepo $internshipRepo */
        $internshipRepo = $entityManager->getRepository(Internship::class);

        /** @var CompanyRepo $companyRepo */
        $companyRepo = $entityManager->getRepository(Company::class);

        $internships = $internshipRepo->getInternships($page, $date, $rating, $skills);
        $internships = array_map(function ($internship) use ($companyRepo) {
            $company = $companyRepo->findByInternshipOffer($internship);
            return [
                "title" => $internship->getTitle(),
                "salary" => $internship->getLowSalary() . " - " . $internship->getHighSalary(),
                "location" => $internship->getLocation()->getZipCode() . " - " . $internship->getLocation()->getCity(),
                "user_wish" => true,
                "company_name" => $company->getName(),
                "company_logo" => $company->getLogoPath(),
            ];
        }, $internships);

        $response->getBody()->write(json_encode($internships));
        return $response->withHeader("Content-Type", "application/json");
    });

    $app->get("/companies/{page}", function (Request $request, Response $response, array $args) use ($entityManager, $logger) {
        $page = $args["page"];

        if ($page < 0) {
            $response->withStatus(404)->getBody()->write(json_encode(["error" => "Page out of range"]));
            return $response;
        }

        $companyRepo = $entityManager->getRepository(Company::class);
        $locationRepo = $entityManager->getRepository(Location::class);

        $companies = $companyRepo->getCompaniesDistinct($page);
        $companies = array_map(function ($company) use ($locationRepo) {
            $location = $locationRepo->findByCompany($company);
            return [
                "name" => $company->getName(),
                "logo" => $company->getLogoPath(),
                "location" => $location->getZipCode() . " - " . $location->getCity(),
            ];
        }, $companies);

        $response->getBody()->write(json_encode($companies));
        return $response->withHeader("Content-Type", "application/json");
    });

    $app->get("/skills/{pattern}", function (Request $request, Response $response, array $args) use ($entityManager, $logger) {
        $pattern = $args["pattern"];

        /** @var SkillRepo $internshipRepo */
        $skillRepo = $entityManager->getRepository(Skill::class);

        $skills = $skillRepo->findSuggestions($pattern);
        $skills = array_map(function ($skill) {
            return [
                "name" => $skill->getName(),
            ];
        }, $skills);

        $response->getBody()->write(json_encode($skills));
        return $response->withHeader("Content-Type", "application/json");
    });
};