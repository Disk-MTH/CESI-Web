<?php

namespace stagify\Controllers;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use stagify\Middlewares\ErrorsMiddleware;
use stagify\Middlewares\FlashMiddleware;
use stagify\Model\Entities\Application;
use stagify\Model\Entities\Company;
use stagify\Model\Entities\Internship;
use stagify\Model\Entities\Location;
use stagify\Model\Entities\Promo;
use stagify\Model\Entities\Skill;
use stagify\Model\Entities\User;
use stagify\Model\Repositories\ApplicationRepo;
use stagify\Model\Repositories\CompanyRepo;
use stagify\Model\Repositories\InternshipRepo;
use stagify\Model\Repositories\LocationRepo;
use stagify\Model\Repositories\PromoRepo;
use stagify\Model\Repositories\SkillRepo;
use Respect\Validation\Validator;
use stagify\Model\Repositories\UserRepo;

class UsersController extends Controller
{
    /** @var UserRepo $userRepo */
    private EntityRepository $userRepo;

    /** @var LocationRepo $locationRepo */
    private EntityRepository $locationRepo;

    /** @var SkillRepo $skillRepo */
    private EntityRepository $skillRepo;

    /** @var PromoRepo $promoRepo */
    private EntityRepository $promoRepo;

    /** @var CompanyRepo $companyRepo */
    private EntityRepository $companyRepo;

    /** @var ApplicationRepo $applicationRepo */
    private EntityRepository $applicationRepo;

    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
        $this->userRepo = $this->entityManager->getRepository(User::class);
        $this->locationRepo = $this->entityManager->getRepository(Location::class);
        $this->skillRepo = $this->entityManager->getRepository(Skill::class);
        $this->promoRepo = $this->entityManager->getRepository(Promo::class);
        $this->companyRepo = $this->entityManager->getRepository(Company::class);
        $this->applicationRepo = $this->entityManager->getRepository(Application::class);
    }

    function users(Request $request, Response $response, array $pathArgs): Response
    {
        $role = $pathArgs["role"];
        if ($role != 2 && $role != 3) return $this->redirect($response, "/users/3");
        return $this->render($response, "pages/users.twig", ["role" => $role]);
    }

    function user(Request $request, Response $response, array $pathArgs): Response
    {
        return $this->render($response, "pages/user.twig", ["user" => $this->userRepo->find($pathArgs["id"])]);
    }

    function wishlist(Request $request, Response $response, array $pathArgs): Response
    {
        $wishes = $this->userRepo->find($pathArgs["id"])->getWishes()->toArray();
        $wishes = array_map(function ($wish) {
            $company = $this->companyRepo->byInternshipId($wish->getId());

            return [
                "url" => "/internship/" . $wish->getId(),
                "startDate" => $wish->getStartDate()->format("d/m/Y"),
                "endDate" => $wish->getEndDate()->format("d/m/Y"),
                "title" => $wish->getTitle(),
                "salary" => $wish->getLowSalary() . " - " . $wish->getHighSalary(),
                "location" => $wish->getLocation()->getZipCode() . " - " . $wish->getLocation()->getCity(),
                "companyName" => $company->getName(),
                "companyLogo" => "/files/companies/" . $company->getLogoPicture(),
            ];
        }, $wishes);

        return $this->render($response, "pages/wishlist.twig", ["wishes" => $wishes]);
    }

    function applications(Request $request, Response $response, array $pathArgs): Response
    {
        $applications = $this->applicationRepo->byUserId($pathArgs["id"]);
        $applications = array_map(function ($application) {
            $company = $this->companyRepo->byInternshipId($application["internshipId"]);

            return [
                "accepted" => $application["accepted"],
                "url" => "/internship/" . $application["internshipId"],
                "internshipTitle" => $application["internshipTitle"],
                "companyName" => $company->getName(),
                "companyLogo" => "/files/companies/" . $company->getLogoPicture(),
            ];
        }, $applications);

        return $this->render($response, "pages/applications.twig", ["applications" => $applications]);
    }

    function createUser(Request $request, Response $response, array $pathArgs): Response
    {
        $role = $pathArgs["role"];
        if ($role != 2 && $role != 3) return $this->redirect($response, "/create/user/3");

        if ($request->getMethod() === "GET") {
            if ($pathArgs["role"] == 2) return $this->render($response, "pages/create_pilot.twig");
            return $this->render($response, "pages/create_student.twig");
        }

        if ($request->getMethod() === "POST") {
//            if ($_POST["_method"] === "POST" || $_POST["_method"] === "PATCH") {
                $data = $request->getParsedBody();
                $files = $request->getUploadedFiles();
                $errors = ErrorsMiddleware::validate($data);
//                $post = $_POST["_method"] === "POST";
                $fail = false;

                $data["role"] = $role;
                $data["profilePicture"] = $files["profilePicture"];

                Validator::notEmpty()->validate($data["lastName"]) || $errors["lastName"] = "Le nom ne peut pas etre vide";
                Validator::notEmpty()->validate($data["firstName"]) || $errors["firstName"] = "Le prenom ne peut pas etre vide";
                Validator::notEmpty()->validate($data["login"]) || $errors["login"] = "Le nom d'utilisateur ne peut pas etre vide";
                Validator::notEmpty()->validate($data["password"]) || $errors["password"] = "Le mot de passe ne peut pas etre vide";
                Validator::notEmpty()->validate($data["zipCode"]) || $errors["zipCode"] = "Le code postal ne peut pas etre vide";
                Validator::notEmpty()->validate($data["city"]) || $errors["city"] = "La ville ne peut pas etre vide";
                Validator::intVal()->positive()->validate($data["profilePicture"]?->getSize()) || $errors["profilePicture"] = "La photo ne peut pas etre vide";
                Validator::notEmpty()->validate($data["description"]) || $errors["description"] = "La description ne peut pas etre vide";

                if ($role == 2) {
                    $data["promos"] = [];
                    foreach ($data as $key => $value) {
                        if (str_starts_with($key, "suggestion-promos_")) {
                            $promo = $this->promoRepo->byConcat($value);
                            if ($promo) $data["promos"][] = $promo;
                            else $errors["promos"] = "La promotion \"" . $value . "\" n'existe pas";
                        }
                    }

                    if (!isset($errors["promos"])) Validator::notEmpty()->validate($data["promos"]) || $errors["promos"] = "Les promotions ne peuvent pas être vides";
                } else {
                    $data["skills"] = [];
                    foreach ($data as $key => $value) {
                        if (str_starts_with($key, "suggestion-skills_")) {
                            $skill = $this->skillRepo->byName($value);
                            if ($skill) $data["skills"][] = $skill;
                            else $errors["skills"] = "La compétence \"" . $value . "\" n'existe pas";
                        }
                    }

                    if (!isset($errors["skills"])) Validator::notEmpty()->validate($data["skills"]) || $errors["skills"] = "Les compétences ne peuvent pas être vides";
                    Validator::notEmpty()->validate($data["school"]) || $errors["school"] = "L'école ne peut pas etre vide";
                    Validator::notEmpty()->validate($data["year"]) || $errors["year"] = "L'année ne peut pas etre vide";
                    Validator::notEmpty()->validate($data["type"]) || $errors["type"] = "La formation ne peut pas etre vide";
                }

                if (empty($errors)) {
                    $data["profilePicture"] = $this->moveFile($data["profilePicture"], "users");
                    if (!$data["profilePicture"]) $errors["profilePicture"] = "La photo n'a pas pu être enregistrée";
                    $data["location"] = $this->locationRepo->byData($data["zipCode"], $data["city"]);
                    if (!$data["location"]) {
                        $data["location"] = $this->locationRepo->create($data);
                        if (!$data["location"]) $errors["zipCode"] = "Une erreur est survenue lors de la création de la localisation";
                    }

                    if ($role == 3) {
                        $data["promo"] = $this->promoRepo->byData($data["year"], $data["type"], $data["school"]);
                        if (!$data["promo"]) {
                            $data["promo"] = $this->promoRepo->create($data);
                            if (!$data["promo"]) $errors["promo"] = "Une erreur est survenue lors de la création de la promotion";
                        }
                    }

                    if (empty($errors)) {
                        if ($this->userRepo->create($data)) FlashMiddleware::flash("success", "L'utilisateur a bien été créé.");
                        else {
                            $fail = true;
                            FlashMiddleware::flash("error", "Une erreur est survenue lors de la création de l'utilisateur.");
                        }
                    } else $fail = true;
                } else $fail = true;
                if ($fail) ErrorsMiddleware::error($errors);
//            }


            /*
             * if ($_POST["_method"] === "DELETE") {
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
             */

        }

        return $this->redirect($response, "/create/user/$role");
    }
}