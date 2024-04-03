<?php

namespace stagify\Controllers;

use Doctrine\ORM\EntityRepository;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use stagify\Middlewares\ErrorsMiddleware;
use stagify\Middlewares\FlashMiddleware;
use stagify\Model\Entities\Promo;
use stagify\Model\Entities\Skill;
use stagify\Model\Repositories\PromoRepo;
use stagify\Model\Repositories\SkillRepo;
use Respect\Validation\Validator;

class UsersController extends Controller
{

    /** @var SkillRepo $skillRepo */
    private EntityRepository $skillRepo;

    /** @var PromoRepo $promoRepo */
    private $promoRepo;

    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
        $this->skillRepo = $this->entityManager->getRepository(Skill::class);
        $this->promoRepo = $this->entityManager->getRepository(Promo::class);
    }

    function users(Request $request, Response $response, array $pathArgs): Response
    {
        $role = $pathArgs["role"];
        if ($role != 2 && $role != 3) return $this->redirect($response, "/users/3");
        return $this->render($response, "pages/users.twig", ["role" => $role]);
    }

    function user(Request $request, Response $response, array $pathArgs): Response
    {
        return $this->render($response, "pages/user.twig");
    }

    function wishlist(Request $request, Response $response): Response
    {
        return $this->render($response, "pages/wishlist.twig");
    }

    function createUser(Request $request, Response $response, array $pathArgs): Response
    {
        $role = $pathArgs["role"];

        $this->logger->info("Role: $role");

        if ($request->getMethod() === "GET") {
            if ($role != 2 && $role != 3) return $this->redirect($response, "/create/user/3");
            if ($pathArgs["role"] == 2) return $this->render($response, "pages/create_pilot.twig");
            return $this->render($response, "pages/create_student.twig");
        }

        if ($request->getMethod() === "POST") {

            $this->logger->info("POST");

            $data = $request->getParsedBody();
            $file = $request->getUploadedFiles();
            $errors = ErrorsMiddleware::validate($data);
            $fail = false;

            $data["role"] = $role;

            $this->logger->info("Data: " . json_encode($data));

            $this->logger->info("File: " . json_encode($file));

            die();

            if ($role == 3) {
                $skills = [];

                foreach ($data as $key => $value) {
                    if (str_starts_with($key, "suggestion@skills_")) {
                        $skill = $this->skillRepo->byName($key);
                        if ($skill) $skills[] = $skill;
                    }
                }

                $this->logger->info("Skills: " . json_encode($skills));

                $data["skills"] = $data["skills"] ?? '';

                Validator::notEmpty()->validate($data["lastName"]) || $errors["lastName"] = "Le nom ne peut pas etre vide";
                Validator::notEmpty()->validate($data["firstName"]) || $errors["firstName"] = "Le prenom ne peut pas etre vide";
                Validator::notEmpty()->validate($data["school"]) || $errors["school"] = "L'ecole ne peut pas etre vide";
                Validator::intVal()->validate($data["year"]) || $errors["year"] = "L'annee ne peut pas etre vide";
                Validator::notEmpty()->validate($data["type"]) || $errors["type"] = "La formation ne peut pas etre vide";
                Validator::notEmpty()->validate($data["username"]) || $errors["username"] = "Le nom d'utilisateur ne peut pas etre vide";
                Validator::notEmpty()->validate($data["password"]) || $errors["password"] = "Le mot de passe ne peut pas etre vide";
                Validator::notEmpty()->validate($data["city"]) || $errors["city"] = "La ville ne peut pas etre vide";
                Validator::notEmpty()->validate($data["zipCode"]) || $errors["zipCode"] = "Le code postal ne peut pas etre vide";
                Validator::notEmpty()->validate($data["skills"]) || $errors["skills"] = "Les competences ne peuvent pas etre vide";
                Validator::file()->validate($file["photoPath"]) || $errors["photoPath"] = "La photo ne peut pas etre vide";
                Validator::notEmpty()->validate($data["description"]) || $errors["description"] = "La description ne peut pas etre vide";

                if (empty($errors)) {
                    FlashMiddleware::flash("success", "L'étudiant a bien été créée.");
                } else $fail = true;
                if ($fail) ErrorsMiddleware::error($errors);
            }

            if ($role == 2) {
                $promos = [];

                foreach ($data as $key => $value) {
                    if (str_starts_with($key, "suggestion@promos_")) {
                        $promo = $this->promoRepo->byConcat($key);
                        if ($promo) $promos[] = $promo;
                    }
                }

                Validator::notEmpty()->validate($data["lastName"]) || $errors["lastName"] = "Le nom ne peut pas etre vide";
                Validator::notEmpty()->validate($data["firstName"]) || $errors["firstName"] = "Le prenom ne peut pas etre vide";
                Validator::notEmpty()->validate($data["username"]) || $errors["username"] = "Le nom d'utilisateur ne peut pas etre vide";
                Validator::notEmpty()->validate($data["password"]) || $errors["password"] = "Le mot de passe ne peut pas etre vide";
                Validator::notEmpty()->validate($data["city"]) || $errors["city"] = "La ville ne peut pas etre vide";
                Validator::notEmpty()->validate($data["zipCode"]) || $errors["zipCode"] = "Le code postal ne peut pas etre vide";
                Validator::file()->validate($data["photoPath"]) || $errors["photoPath"] = "La photo ne peut pas etre vide";
                Validator::notEmpty()->validate($data["description"]) || $errors["description"] = "La description ne peut pas etre vide";
                Validator::notEmpty()->validate($data["promos"]) || $errors["promos"] = "Les promotions ne peuvent pas etre vide";

                if (empty($errors)) {
                    FlashMiddleware::flash("success", "L'étudiant a bien été créée.");
                } else $fail = true;
                if ($fail) ErrorsMiddleware::error($errors);

            }
        }
        return $this->redirect($response, "/create/user/$role");
    }
}