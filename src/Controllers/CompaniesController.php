<?php

namespace stagify\Controllers;

use Doctrine\ORM\EntityRepository;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use stagify\Model\Entities\Company;
use stagify\Model\Repositories\CompanyRepo;

class CompaniesController extends Controller
{
    /** @var CompanyRepo $companyRepo */
    private EntityRepository $companyRepo;

    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
        $this->companyRepo = $this->entityManager->getRepository(Company::class);
    }

    function companies(Request $request, Response $response): Response
    {
        return $this->render($response, "pages/companies.twig");
    }

    function company(Request $request, Response $response): Response
    {
        return $this->render($response, "pages/company.twig");
    }

    function rating(Request $request, Response $response): Response
    {
        return $this->render($response, "pages/company_rating.twig");
    }

    function createCompany(Request $request, Response $response): Response
    {
        if ($request->getMethod() === "GET") {
            return $this->render($response, "pages/create_company.twig");
        }

        if ($request->getMethod() === "POST") {
            /*$data = $request->getParsedBody();
            $uploadedFiles = $request->getUploadedFiles();

            $logger->debug("Creating company with data: " . json_encode($data));
            $logger->debug("Uploaded files: " . print_r($uploadedFiles, true));

            $errors = OldDataMiddleware::validate($data);

            Validator::notEmpty()->validate($data["name"]) || $errors["name"] = "Le nom ne peut pas être vide";
            Validator::notEmpty()->validate($uploadedFiles["logo"]) || $errors["logo"] = "Le logo ne peut pas être vide";
            Validator::notEmpty()->validate($data["sector"]) || $errors["sector"] = "Le secteur ne peut pas être vide";
            Validator::intType()->validate($data["zipCode"]) || $errors["zipCode"] = "Le code postal doit être un nombre";
            Validator::notEmpty()->validate($data["city"]) || $errors["city"] = "La ville ne peut pas être vide";
            Validator::intType()->validate($data["employees"]) || $errors["employees"] = "Le nombre d'employés doit être un nombre";
            Validator::url()->validate($data["website"]) || $errors["website"] = "Le site web n'est pas valide";

            if (empty($errors)) {
                $uploadedFile = $uploadedFiles["logo"];

                $Company = new Company();
                $Company->setName($data["name"]);
                $Company->setWebsite($data["website"]);
                $Company->setEmployeeCount($data["employees"]);

                $filename = moveUploadedFile($fileDirectory, $uploadedFile);
                $Company->setLogoPath($filename);

                $ActivitySector = new ActivitySector();
                $ActivitySector->setName($data["sector"]);

                $Company->setActivitySector($ActivitySector);

                $Location = new Location();
                $Location->setZipCode($data["zipCode"]);
                $Location->setCity($data["city"]);

                $Company->addLocation($Location);

                $entityManager->persist($Company);
                $entityManager->flush();

                FlashMiddleware::flash("success", "Entreprise créée avec succès");
                return redirect($response, "/");
            }

            ErrorsMiddleware::error($errors);
            return redirect($response, "create-company");*/
        }

        return $this->redirect($response, "/create/company");
    }
}