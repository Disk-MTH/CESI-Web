<?php

namespace stagify\Controllers;

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
use stagify\Model\Entities\Promo;
use stagify\Model\Entities\Skill;
use stagify\Model\Repositories\CompanyRepo;
use stagify\Model\Repositories\InternshipRepo;
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

    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
        $this->internshipRepo = $this->entityManager->getRepository(Internship::class);
        $this->companyRepo = $this->entityManager->getRepository(Company::class);
        $this->promoRepo = $this->entityManager->getRepository(Promo::class);
        $this->skillRepo = $this->entityManager->getRepository(Skill::class);
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
            $skills = [];
            $promos = [];

            foreach ($data as $key => $value) {
                if (str_starts_with($key, "suggestion@skills_")) {
                    $skill = $this->skillRepo->byName($key);
                    if ($skill) $skills[] = $skill;
                    //TODO errors
                }
                if (str_starts_with($key, "suggestion@promos_")) {
                    $promo = $this->promoRepo->byConcat($key);
                    if ($promo) $promos[] = $promo;
                }
            }

            $data = array_merge($data, ["skills" => $skills, "promos" => $promos]);

            Validator::notEmpty()->validate($data["company"]) || $errors["companies"] = "L'entreprise n'est pas valide";
            Validator::notEmpty()->validate($data["title"]) || $errors["title"] = "Le titre ne peut pas être vide";
            Validator::date()->validate($data["startDate"]) || $errors["startDate"] = "La date de début n'est pas valide";
            Validator::date()->validate($data["endDate"]) || $errors["endDate"] = "La date de fin n'est pas valide";
            Validator::intType()->validate($data["duration"]) || $errors["duration"] = "La durée doit être un nombre";
            Validator::intType()->validate($data["lowSalary"]) || $errors["lowSalary"] = "Le salaire minimum doit être un nombre";
            Validator::intType()->validate($data["highSalary"]) || $errors["highSalary"] = "Le salaire maximum doit être un nombre";
            Validator::notEmpty()->validate($data["description"]) || $errors["description"] = "La description ne peut pas être vide";
            Validator::notEmpty()->validate($data["skills"]) || $errors["skills"] = "Les compétences ne peuvent pas être vides";
            Validator::notEmpty()->validate($data["promos"]) || $errors["promos"] = "Les promotions ne peuvent pas être vides";

            if (empty($errors)) {
                FlashMiddleware::flash("success", "L'offre de stage a bien été créée.");
                /*if ($company) {
                    $company = $this->companyRepo->byConcat($data["companiesField"]);




                    FlashMiddleware::flash("success", "L'offre de stage a bien été créée.");
                } else {
                    $fail = true;
                    FlashMiddleware::flash("error", "L'entreprise n'existe pas.");
                }*/





                /*
                if ($this->internshipRepo->create($data)) {
                    FlashMiddleware::flash("success", "L'offre de stage a bien été créée.");
                } else {
                    $fail = true;
                    FlashMiddleware::flash("error", "Une erreur est survenue lors de la création de l'offre de stage.");
                }*/
            }
            else $fail = true;
            if ($fail) ErrorsMiddleware::error($errors);
        }

        return $this->redirect($response, "/create/internship");
    }
}