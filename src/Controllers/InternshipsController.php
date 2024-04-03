<?php

namespace stagify\Controllers;

use DateTime;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Respect\Validation\Validator;
use stagify\Middlewares\ErrorsMiddleware;
use stagify\Middlewares\FlashMiddleware;
use stagify\Model\Entities\Company;
use stagify\Model\Entities\Internship;
use stagify\Model\Entities\Location;
use stagify\Model\Entities\Promo;
use stagify\Model\Entities\Skill;
use stagify\Model\Repositories\CompanyRepo;
use stagify\Model\Repositories\InternshipRepo;
use stagify\Model\Repositories\LocationRepo;
use stagify\Model\Repositories\PromoRepo;
use stagify\Model\Repositories\SkillRepo;
use Throwable;

class InternshipsController extends Controller
{
    /** @var InternshipRepo $internshipRepo */
    private EntityRepository $internshipRepo;

    /** @var CompanyRepo $companyRepo */
    private EntityRepository $companyRepo;

    /** @var PromoRepo $promoRepo */
    private EntityRepository $promoRepo;

    /** @var SkillRepo $skillRepo */
    private EntityRepository $skillRepo;

    /** @var LocationRepo $locationRepo */
    private EntityRepository $locationRepo;

    private EntityManager $em;

    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
        $this->internshipRepo = $this->entityManager->getRepository(Internship::class);
        $this->companyRepo = $this->entityManager->getRepository(Company::class);
        $this->promoRepo = $this->entityManager->getRepository(Promo::class);
        $this->skillRepo = $this->entityManager->getRepository(Skill::class);
        $this->locationRepo = $this->entityManager->getRepository(Location::class);

        $this->em = $container->get("entityManager");

    }

    function internships(Request $request, Response $response): Response
    {
        $total = $request->getQueryParams()["count"] ?? false;
        if ($total) return $this->json($response, ["count" => $this->internshipRepo->count([])]);
        return $this->render($response, "pages/internships.twig");
    }

    function internship(Request $request, Response $response): Response
    {
        $id = $request->getQueryParams()["id"] ?? null;

        $data = [];

        if ($id) {
            $internship = $this->internshipRepo->find($id);
            $company = $this->companyRepo->byInternshipId($id);
            if ($internship && $company) {
                $data["id"] = $internship->getId();
                $data["title"] = $internship->getTitle();
                $data["startDate"] = $internship->getStartDate()->format("Y-m-d");
                $data["endDate"] = $internship->getEndDate()->format("Y-m-d");
                $data["duration"] = $internship->getDurationDays();
                $data["lowSalary"] = $internship->getLowSalary();
                $data["highSalary"] = $internship->getHighSalary();
                $data["placesCount"] = $internship->getPlaceCount();
                $data["description"] = $internship->getDescription();
                $data["company"] = $company->getName();
                $data["location"] = $internship->getLocation()->getZipCode() . " " . $internship->getLocation()->getCity();
                $data["skills"] = [];
                foreach ($internship->getSkills() as $skills) $data["skills"][] = $skills->getName();
                $data["promos"] = [];
                foreach ($internship->getPromos() as $promo) $data["promos"][] = "A" . $promo->getYear() . " " . $promo->getType() . " - " . $promo->getSchool();
            }
        }

        return $this->render($response, "pages/internship.twig", $data);
    }

    function rating(Request $request, Response $response): Response
    {
        return $this->render($response, "pages/internships_rating.twig");
    }

    function apply(Request $request, Response $response): Response
    {
        return $this->render($response, "pages/apply_internship.twig");
    }

    /** @throws Throwable */
    function createInternship(Request $request, Response $response): Response
    {
        if ($request->getMethod() === "GET") {
            $queryParams = $request->getQueryParams();
            $edit = $queryParams["edit"] ?? false;
            $id = $queryParams["id"] ?? null;
            $args = [];
            $data = [];

            if ($edit && $id) {
                $internship = $this->internshipRepo->find($id);
                $company = $this->companyRepo->byInternshipId($id);
                if ($internship && $company) {
                    $data["id"] = $internship->getId();
                    $data["title"] = $internship->getTitle();
                    $data["startDate"] = $internship->getStartDate()->format("Y-m-d");
                    $data["endDate"] = $internship->getEndDate()->format("Y-m-d");
                    $data["duration"] = $internship->getDurationDays();
                    $data["lowSalary"] = $internship->getLowSalary();
                    $data["highSalary"] = $internship->getHighSalary();
                    $data["placesCount"] = $internship->getPlaceCount();
                    $data["description"] = $internship->getDescription();
                    $data["companiesField"] = $company->getName() . " - " . $internship->getLocation()->getZipCode() . " " . $internship->getLocation()->getCity();
                    $data["skills"] = [];
                    foreach ($internship->getSkills() as $skills) $data["suggestion-skills_" . bin2hex(random_bytes(10))] = $skills->getName();
                    $data["promos"] = [];
                    foreach ($internship->getPromos() as $promo) $data["suggestion-promos_" . bin2hex(random_bytes(10))] = "A" . $promo->getYear() . " " . $promo->getType() . " - " . $promo->getSchool();

                    $args["edit"] = $edit;
                    $args["id"] = $id;
                    $args["deleted"] = $internship->getDeleted();
                    $args["old"] = $data;
                }
            }

            return $this->render($response, "pages/create_internship.twig", $args);
        }

        if ($request->getMethod() === "POST") {
            if ($_POST["_method"] === "POST" || $_POST["_method"] === "PATCH") {
                $data = $request->getParsedBody();
                $errors = ErrorsMiddleware::validate($data);

                $data["company"] = $this->companyRepo->byConcat($data["companiesField"]);
                $data["promos"] = [];
                $data["skillsString"] = [];
                foreach ($data as $key => $value) {
                    if (str_starts_with($key, "suggestion-skills_")) $data["skillsString"][] = $value;
                    if (str_starts_with($key, "suggestion-promos_")) {
                        $promo = $this->promoRepo->byConcat($value);
                        if ($promo) $data["promos"][] = $promo;
                        else $errors["promos"] = "La promotion \"" . $value . "\" n'existe pas";
                    }
                }

                Validator::notEmpty()->validate($data["company"]) || $errors["companies"] = "L'entreprise n'est pas valide";
                Validator::notEmpty()->validate($data["title"]) || $errors["title"] = "Le titre ne peut pas être vide";
                Validator::date()->validate($data["startDate"]) || $errors["startDate"] = "La date de début n'est pas valide";
                Validator::date()->validate($data["endDate"]) || $errors["endDate"] = "La date de fin n'est pas valide";
                Validator::intVal()->validate($data["duration"]) || $errors["duration"] = "La durée doit être un nombre";
                Validator::intVal()->validate($data["lowSalary"]) || $errors["lowSalary"] = "Le salaire minimum doit être un nombre";
                Validator::intVal()->validate($data["highSalary"]) || $errors["highSalary"] = "Le salaire maximum doit être un nombre";
                Validator::intVal()->positive()->validate($data["placesCount"]) || $errors["placesCount"] = "Le nombre de places doit être un nombre positif";
                Validator::notEmpty()->validate($data["description"]) || $errors["description"] = "La description ne peut pas être vide";
                if (!isset($errors["promos"])) Validator::notEmpty()->validate($data["promos"]) || $errors["promos"] = "Les promotions ne peuvent pas être vides";
                Validator::notEmpty()->validate($data["skillsString"]) || $errors["skills"] = "Les compétences ne peuvent pas être vides";

                if ($data["company"]) $data["location"] = $this->locationRepo->byConcat(explode(" - ", $data["companiesField"])[1]);
                else $errors["companies"] = "L'entreprise n'existe pas";
                $data["startDate"] = DateTime::createFromFormat("Y-m-d", $data["startDate"]);
                $data["endDate"] = DateTime::createFromFormat("Y-m-d", $data["endDate"]);
                if ($data["startDate"] && $data["endDate"]) {
                    if ($data["startDate"] >= $data["endDate"]) $errors["endDate"] = "La date de début doit être inférieure à la date de fin";
                    else {
                        $interval = $data["startDate"]->diff($data["endDate"]);
                        if ($interval->days < $data["duration"]) $errors["duration"] = "La durée doit être inférieure ou égale à la différence entre la date de début et la date de fin";
                    }
                }

                if ($data["lowSalary"] && $data["highSalary"]) {
                    if ($data["highSalary"] < $data["lowSalary"]) $errors["highSalary"] = "Le salaire maximum doit être supérieur au salaire minimum";
                }

                if (empty($errors)) {
                    $data["skills"] = [];
                    foreach ($data["skillsString"] as $value) {
                        $skill = $this->skillRepo->byName($value);
                        if (!$skill) {
                            $skill = $this->skillRepo->create($value);
                            if (!$skill) $errors["skills"] = "Une erreur est survenue lors de la création de la compétence \"" . $value . "\"";
                        }
                        if ($skill) $data["skills"][] = $skill;
                    }

                    if (!isset($errors["skills"])) Validator::notEmpty()->validate($data["skills"]) || $errors["skills"] = "Les compétences ne peuvent pas être vides";

                    if (empty($errors)) {
                        $internship = $_POST["_method"] === "POST" ? $this->internshipRepo->create($data) : $this->internshipRepo->update($data);
                        if ($internship) {
                            FlashMiddleware::flash("success", "Offre de stage enregistrée avec succès.");
                            return $this->redirect($response, "/create/internship?edit=true&id=" . $internship->getId());
                        } else FlashMiddleware::flash("error", "Une erreur est survenue lors de la modification de l'offre de stage.");
                    }
                }
                ErrorsMiddleware::error($errors);
            }

            if ($_POST["_method"] === "DELETE") {
                $data = $request->getParsedBody();
                $id = $data["id"];

                if (Validator::intVal()->validate($id) && $this->internshipRepo->delete($id)) FlashMiddleware::flash("success", "L'offre de stage a bien été supprimée.");
                else FlashMiddleware::flash("error", "Une erreur est survenue lors de la suppression de l'offre de stage.");
                return $this->redirect($response, "/create/internship?edit=true&id=" . $data["id"]);
            }

            if ($_POST["_method"] === "RESTORE") {
                $data = $request->getParsedBody();
                $id = $data["id"];

                if (Validator::intVal()->validate($id) && $this->internshipRepo->restore($id)) FlashMiddleware::flash("success", "L'offre de stage a bien été retaurée.");
                else FlashMiddleware::flash("error", "Une erreur est survenue lors de la restauration de l'offre de stage.");
                return $this->redirect($response, "/create/internship?edit=true&id=" . $data["id"]);
            }
        }

        return $this->redirect($response, "/create/internship");
    }
}