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
use stagify\Model\Entities\Application;
use stagify\Model\Entities\Company;
use stagify\Model\Entities\Internship;
use stagify\Model\Entities\Location;
use stagify\Model\Entities\Promo;
use stagify\Model\Entities\Rate;
use stagify\Model\Entities\Skill;
use stagify\Model\Entities\User;
use stagify\Model\Repositories\ApplicationRepo;
use stagify\Model\Repositories\CompanyRepo;
use stagify\Model\Repositories\InternshipRepo;
use stagify\Model\Repositories\LocationRepo;
use stagify\Model\Repositories\PromoRepo;
use stagify\Model\Repositories\RateRepo;
use stagify\Model\Repositories\SkillRepo;
use stagify\Model\Repositories\UserRepo;
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

    /** @var UserRepo $userRepo */
    private EntityRepository $userRepo;

    /** @var RateRepo $rateRepo */
    private EntityRepository $rateRepo;

    /** @var ApplicationRepo $applicationRepo */
    private EntityRepository $applicationRepo;


    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
        $this->internshipRepo = $this->entityManager->getRepository(Internship::class);
        $this->companyRepo = $this->entityManager->getRepository(Company::class);
        $this->promoRepo = $this->entityManager->getRepository(Promo::class);
        $this->skillRepo = $this->entityManager->getRepository(Skill::class);
        $this->locationRepo = $this->entityManager->getRepository(Location::class);
        $this->userRepo = $this->entityManager->getRepository(User::class);
        $this->rateRepo = $this->entityManager->getRepository(Rate::class);
        $this->applicationRepo = $this->entityManager->getRepository(Application::class);

    }

    function internships(Request $request, Response $response): Response
    {
        $total = $request->getQueryParams()["count"] ?? false;
        if ($total) return $this->json($response, ["count" => $this->internshipRepo->count([])]);
        return $this->render($response, "pages/internships.twig");
    }

    function internship(Request $request, Response $response, array $pathArgs): Response
    {
        return $this->render($response, "pages/internship.twig", ["internship" => $this->internshipRepo->find($pathArgs["id"]), "company" => $this->companyRepo->byInternshipId($pathArgs["id"])]);
    }

    function rating(Request $request, Response $response, array $pathParams): Response
    {
        if ($request->getMethod() === "GET") {

            $internship = $this->internshipRepo->find($pathParams["id"]);
            $internshipRates = $internship->getRates();
            $rates = [];
            for ($i = 0; $i < count($internshipRates); $i++) {
                $rates[$i]["rate"] = $internshipRates[$i];
                $rates[$i]["users"] = $this->userRepo->findByRate($internshipRates[$i]);
            }

            $company = $this->companyRepo->byInternshipId($pathParams["id"]);
        }

        if ($request->getMethod() === "POST") {

            $data = $request->getParsedBody();
            $errors = ErrorsMiddleware::validate($data);

            $data["internship"] = $this->internshipRepo->find($pathParams["id"]);
            $data["user"] = $this->userRepo->find($_SESSION["user"]);

            Validator::intVal()->validate($data["job_rating"]) || $errors["rate"] = "La note ne peut pas être vide";
            Validator::notEmpty()->validate($data["description"]) || $errors["description"] = "La description ne peut pas être vide";

            $this->logger->info(json_encode($data));

            if (empty($errors)) {
                $rate = $this->rateRepo->create($data);
                if ($rate) {
                    FlashMiddleware::flash("success", "Note enregistrée avec succès.");
                    return $this->redirect($response, "/internship/" . $pathParams["id"] . "/rating");
                }
            }
        }

        return $this->render($response, "pages/internship_rating.twig", ["internship" => $internship, "rates" => $rates, "company" => $company]);
    }

    function apply(Request $request, Response $response, array $pathArgs): Response
    {

        if ($request->getMethod() === "POST") {
            $files = $request->getUploadedFiles();
            $data = [];
            $errors = ErrorsMiddleware::validate($data);
            $fail = false;

            $data["internship"] = $this->internshipRepo->find($pathArgs["id"]);
            $data["user"] = $this->userRepo->find($_SESSION["user"]);
            $data["cv"] = $files["cv"];
            $data["coverLetter"] = $files["coverLetter"];

            $this->logger->info(json_encode($data));

            Validator::intVal()->positive()->validate($data["cv"]) || $errors["cv"] = "Le CV est invalide";
            Validator::intVal()->positive()->validate($data["coverLetter"]) || $errors["coverLetter"] = "La lettre de motivation est invalide";

            if (empty($errors)) {
                $data["cv"] = $this->moveFile($data["cv"], "internship/cv");
                if (!$data["cv"]) $errors["cv"] = "Le cv n'a pas pu être enregistrée";

                $data["coverLetter"] = $this->moveFile($data["coverLetter"], "internship/coverLetter");
                if (!$data["coverLetter"]) $errors["coverLetter"] = "La lettre de motivation n'a pas pu être enregistrée";

                if (empty($errors)) {
                    $application = $this->applicationRepo->create($data);
                    if ($application) {
                        FlashMiddleware::flash("success", "Candidature enregistrée avec succès.");
                        return $this->redirect($response, "/internship/" . $pathArgs["id"] . "/apply");
                    }
                } else $fail = true;
            } else $fail = true;
            if ($fail) ErrorsMiddleware::error($errors);
        }

        return $this->render($response, "pages/apply_internship.twig", ["internship" => $this->internshipRepo->find($pathArgs["id"]), "company" => $this->companyRepo->byInternshipId($pathArgs["id"])]);
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
                    foreach ($internship->getSkills() as $skill) $data["suggestion-skills_" . bin2hex(random_bytes(10))] = $skill->getName();
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
                $post = $_POST["_method"] === "POST";

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
                        $internship = $post ? $this->internshipRepo->create($data) : $this->internshipRepo->update($data);
                        if ($internship) {
                            FlashMiddleware::flash("success", "Offre de stage enregistrée avec succès.");
                            if ($post) return $this->redirect($response, "/create/internship");
                            else return $this->redirect($response, "/internships");
                        } else {
                            FlashMiddleware::flash("error", "Une erreur est survenue lors de la modification de l'offre de stage.");
                            if (!$post) return $this->redirect($response, "/create/internship?edit=true&id=" . $data["id"]);
                        }
                    }
                }
                ErrorsMiddleware::error($errors);
                if (!$post) return $this->redirect($response, "/create/internship?edit=true&id=" . $data["id"]);
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