<?php

use Doctrine\ORM\EntityManager;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Log\LoggerInterface as Logger;
use Slim\App;
use Slim\Views\Twig;
use stagify\Model\Entities\Company;
use stagify\Model\Entities\InternshipOffer;
use stagify\Model\Entities\Location;
use stagify\Model\Repositories\CompanyRepo;
use stagify\Model\Repositories\InternshipOfferRepo;

return function (App $app, Logger $logger, Twig $twig, EntityManager $entityManager) {
    $app->get("/internships/{page}", function (Request $request, Response $response, array $args) use ($entityManager, $logger) {
        $page = $args["page"];

        if ($page < 0) {
            $response->withStatus(404)->getBody()->write(json_encode(["error" => "Page out of range"]));
            return $response;
        }

        /** @var InternshipOfferRepo $internshipRepo */
        $internshipRepo = $entityManager->getRepository(InternshipOffer::class);

        /** @var CompanyRepo $companyRepo */
        $companyRepo = $entityManager->getRepository(Company::class);

        $internships = $internshipRepo->getInternshipOffers($page);
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
};