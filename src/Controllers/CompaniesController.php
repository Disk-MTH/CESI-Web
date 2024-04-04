<?php

namespace stagify\Controllers;

use Doctrine\ORM\EntityRepository;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Respect\Validation\Validator;
use stagify\Middlewares\ErrorsMiddleware;
use stagify\Middlewares\FlashMiddleware;
use stagify\Model\Entities\ActivitySector;
use stagify\Model\Entities\Company;
use stagify\Model\Entities\Location;
use stagify\Model\Repositories\ActivitySectorRepo;
use stagify\Model\Repositories\CompanyRepo;
use stagify\Model\Repositories\LocationRepo;
use Throwable;

class CompaniesController extends Controller
{
    /** @var CompanyRepo $companyRepo */
    private EntityRepository $companyRepo;

    /** @var LocationRepo $locationRepo */
    private EntityRepository $locationRepo;

    /** @var ActivitySectorRepo $locationRepo */
    private EntityRepository $activitySectorRepo;

    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
        $this->companyRepo = $this->entityManager->getRepository(Company::class);
        $this->locationRepo = $this->entityManager->getRepository(Location::class);
        $this->activitySectorRepo = $this->entityManager->getRepository(ActivitySector::class);
    }

    function companies(Request $request, Response $response): Response
    {
        return $this->render($response, "pages/companies.twig");
    }

    function company(Request $request, Response $response, array $pathArgs): Response
    {
        return $this->render($response, "pages/company.twig", [
            "company" => $this->companyRepo->find($pathArgs["id"]),
            "location" => $this->locationRepo->find($pathArgs["location"])
        ]);
    }

    function rating(Request $request, Response $response): Response
    {
        return $this->render($response, "pages/company_rating.twig");
    }

    /** @throws Throwable */
    function createCompany(Request $request, Response $response): Response
    {
        if ($request->getMethod() === "GET") {
            $queryParams = $request->getQueryParams();
            $edit = $queryParams["edit"] ?? false;
            $id = $queryParams["id"] ?? null;
            $args = [];
            $data = [];

            if ($edit && $id) {
                $company = $this->companyRepo->find($id);
                if ($company) {
                    $data["id"] = $company->getId();
                    $data["companyName"] = $company->getName();
                    $data["employeeCount"] = $company->getEmployeeCount();
                    $data["website"] = $company->getWebsite();
                    $data["sectorsField"] = $company->getActivitySector()->getName();
                    $data["locations"] = [];
                    foreach ($company->getLocations() as $location) $data["suggestion-locations_" . bin2hex(random_bytes(10))] = $location->getZipCode() . " " . $location->getCity();

                    $args["edit"] = $edit;
                    $args["id"] = $id;
                    $args["deleted"] = $company->getDeleted();
                    $args["old"] = $data;
                }
            }

            return $this->render($response, "pages/create_company.twig", $args);
        }

        if ($request->getMethod() === "POST") {
            if ($_POST["_method"] === "POST" || $_POST["_method"] === "PATCH") {
                $data = $request->getParsedBody();
                $files = $request->getUploadedFiles();
                $errors = ErrorsMiddleware::validate($data);
                $post = $_POST["_method"] === "POST";

                $data["sector"] = $this->activitySectorRepo->byName($data["sectorsField"]);
                $data["logoPicture"] = $files["logoPicture"];
                $data["locationsString"] = [];
                foreach ($data as $key => $value) {
                    if (str_starts_with($key, "suggestion-locations_")) $data["locationsString"][] = $value;
                }

                Validator::notEmpty()->validate($data["companyName"]) || $errors["companyName"] = "Le nom de l'entreprise ne peut pas etre vide";
                Validator::intVal()->validate($data["employeeCount"]) || $errors["employeeCount"] = "Le nombre d'employés doit être un nombre";
                Validator::url()->validate($data["website"]) || $errors["website"] = "Le site web n'est pas valide";
                Validator::notEmpty()->validate($data["sectorsField"]) || $errors["sectors"] = "Le secteur ne peut pas être vide";
                Validator::intVal()->positive()->validate($data["logoPicture"]?->getSize()) || $errors["logoPicture"] = "Le logo ne peut pas être vide";
                Validator::notEmpty()->validate($data["locationsString"]) || $errors["locations"] = "Les localisations ne peuvent pas être vide";

                $this->logger->warning(json_encode($errors));

                if (empty($errors)) {
                    $data["logoPicture"] = $this->moveFile($data["logoPicture"], "companies");
                    if (!$data["logoPicture"]) $errors["logoPicture"] = "La photo n'a pas pu être enregistrée";

                    $data["locations"] = [];
                    foreach ($data["locationsString"] as $value) {
                        $location = $this->locationRepo->byConcat($value);
                        if (!$location) {
                            $split = explode(" ", $value);
                            $location = $this->locationRepo->create(["zipCode" => $split[0], "city" => implode(" ", array_slice($split, 1))]);
                            if (!$location) $errors["locations"] = "Une erreur est survenue lors de la création de la localisation \"" . $value . "\"";
                        }
                        if ($location) $data["locations"][] = $location;
                    }

                    if (!isset($errors["locations"])) Validator::notEmpty()->validate($data["locations"]) || $errors["locations"] = "Les localisations ne peuvent pas être vides";

                    if (!$data["sector"]) {
                        $sector = $this->activitySectorRepo->create(["name" => $data["sectorsField"]]);
                        if ($sector) $data["sector"] = $sector;
                        else $errors["sectors"] = "Une erreur est survenue lors de la création du secteur";
                    }

                    if (empty($errors)) {
                        $company = $post ? $this->companyRepo->create($data) : $this->companyRepo->update($data);
                        if ($company) {
                            FlashMiddleware::flash("success", "Entreprise enregistrée avec succès.");
                            if ($post) return $this->redirect($response, "/create/company" . $company->getId());
                            else return $this->redirect($response, "/companies");
                        } else {
                            FlashMiddleware::flash("error", "Une erreur est survenue lors de la modification de l'entreprise.");
                            if (!$post) return $this->redirect($response, "/create/company?edit=true&id=" . $data["id"]);
                        }
                    }
                }
                ErrorsMiddleware::error($errors);
                if (!$post) return $this->redirect($response, "/create/company?edit=true&id=" . $data["id"]);
            }

            if ($_POST["_method"] === "DELETE") {
                $data = $request->getParsedBody();
                $id = $data["id"];

                if (Validator::intVal()->validate($id) && $this->companyRepo->delete($id)) FlashMiddleware::flash("success", "L'entreprise a bien été supprimée.");
                else FlashMiddleware::flash("error", "Une erreur est survenue lors de la suppression de l'entreprise.");
                return $this->redirect($response, "/create/company?edit=true&id=" . $data["id"]);
            }

            if ($_POST["_method"] === "RESTORE") {
                $data = $request->getParsedBody();
                $id = $data["id"];

                if (Validator::intVal()->validate($id) && $this->companyRepo->restore($id)) FlashMiddleware::flash("success", "L'entreprise de stage a bien été retaurée.");
                else FlashMiddleware::flash("error", "Une erreur est survenue lors de la restauration de l'entreprise.");
                return $this->redirect($response, "/create/company?edit=true&id=" . $data["id"]);
            }
        }

        return $this->redirect($response, "/create/company");
    }
}