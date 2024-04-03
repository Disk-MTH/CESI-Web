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
use stagify\Middlewares\OldDataMiddleware;
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
        return $this->render($response, "pages/internship.twig");
    }

    function rating(Request $request, Response $response): Response
    {
        return $this->render($response, "pages/internships_rating.twig");
    }

    function apply(Request $request, Response $response): Response
    {
        return $this->render($response, "pages/apply_internship.twig");
    }

    function createInternship(Request $request, Response $response): Response
    {
        if ($request->getMethod() === "GET") {
            return $this->render($response, "pages/create_internship.twig");
        }

        if ($request->getMethod() === "POST") {
            $data = $request->getParsedBody();
            $errors = ErrorsMiddleware::validate($data);
            $fail = false;

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

            $data["location"] = $this->locationRepo->byConcat(explode(" - ", $data["companiesField"])[1]);
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
                    if ($this->internshipRepo->create($data)) {
                        FlashMiddleware::flash("success", "L'offre de stage a bien été créée.");
                    } else {
                        $fail = true;
                        FlashMiddleware::flash("error", "Une erreur est survenue lors de la création de l'offre de stage.");
                    }
                } else $fail = true;
            } else $fail = true;
            if ($fail) ErrorsMiddleware::error($errors);
        }

        return $this->redirect($response, "/create/internship");
    }
}