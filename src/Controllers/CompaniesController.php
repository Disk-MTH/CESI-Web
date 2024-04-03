<?php

namespace stagify\Controllers;

use Doctrine\ORM\EntityRepository;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Respect\Validation\Validator;
use stagify\Middlewares\ErrorsMiddleware;
use stagify\Middlewares\FlashMiddleware;
use stagify\Model\Entities\Company;
use stagify\Model\Entities\Location;
use stagify\Model\Repositories\CompanyRepo;

class CompaniesController extends Controller
{
    /** @var CompanyRepo $companyRepo */
    private EntityRepository $companyRepo;

    /** @var LocationRepo $locationRepo */
    private EntityRepository $locationRepo;

    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
        $this->companyRepo = $this->entityManager->getRepository(Company::class);
        $this->locationRepo = $this->entityManager->getRepository(Location::class);
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
            $data = $request->getParsedBody();
            $files = $request->getUploadedFiles();
            $errors = ErrorsMiddleware::validate($data);
            $fail = false;

            $data["logo"] = $files["logoPicture"];

            Validator::notEmpty()->validate($data["companyName"]) || $errors["companyName"] = "Le nom de l'entreprise ne peut pas etre vide";
            Validator::notEmpty()->validate($data["employeeCount"]) || $errors["employeeCount"] = "Le nombre d'employes ne peut pas etre vide";
            Validator::notEmpty()->validate($data["website"]) || $errors["website"] = "Le site web ne peut pas etre vide";
            Validator::notEmpty()->validate($data["sector"]) || $errors["sector"] = "Le secteur ne peut pas etre vide";
            Validator::notEmpty()->validate($data["city"]) || $errors["city"] = "La ville ne peut pas etre vide";
            Validator::notEmpty()->validate($data["zipCode"]) || $errors["zipCode"] = "Le code postal ne peut pas etre vide";
            Validator::intVal()->positive()->validate($data["logo"]?->getSize()) || $errors["logo"] = "Le logo ne peut pas etre vide";

            if (empty($errors)) {

                $data["logoPicture"] = $this->moveFile($data["logoPicture"], "companies");
                if (!$data["logoPicture"]) $errors["logoPicture"] = "La photo n'a pas pu être enregistrée";
                $data["location"] = $this->locationRepo->byData($data["zipCode"], $data["city"]);
                if (!$data["location"]) {
                    $data["location"] = $this->locationRepo->create($data);
                    if (!$data["location"]) $errors["zipCode"] = "Une erreur est survenue lors de la création de la localisation";
                }

                if (empty($errors)) {
                    if ($this->companyRepo->create($data)) FlashMiddleware::flash("success", "L'utilisateur a bien été créé.");
                    else {
                        $fail = true;
                        FlashMiddleware::flash("error", "Une erreur est survenue lors de la création de l'utilisateur.");
                    }
                } else $fail = true;
            } else $fail = true;
            if ($fail) ErrorsMiddleware::error($errors);
        }
        return $this->redirect($response, "/create/company");
    }
}