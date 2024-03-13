<?php

namespace stagify;

use Doctrine\ORM\EntityManager;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Log\LoggerInterface as Logger;
use Respect\Validation\Validator;
use Slim\App;
use stagify\Flash\Flash;
use stagify\Flash\FlashStatus;
use stagify\Model\Entities\User;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

function redirect(Response $response, string $url): Response
{
    return $response->withStatus(302)->withHeader("Location", $url);
}

/**
 * @throws SyntaxError
 * @throws RuntimeError
 * @throws LoaderError
 */
function render(Response $response, string $template, array $data = []): Response
{
    global $twig;
    return $twig->render($response, $template, $data);
}

function flash(Flash $flash): void
{
    $_SESSION["flash"] = $flash;
}

return function (App $app, Logger $logger, EntityManager $entityManager) {
    $app->get("/list", function (Request $request, Response $response) use ($entityManager) {
        $users = $entityManager->getRepository(User::class)->findAll();
        return render($response, "temp/list.twig", ["users" => $users]);
    })->setName("list");

    $app->get("/form", function (Request $request, Response $response) use ($logger) {
        return render($response, "temp/form.twig");
    })->setName("form");

    $app->post("/form", function (Request $request, Response $response) use ($entityManager) {
        $data = $request->getParsedBody();
        $errors = [];

        foreach ($data as $key => $value) {
            if (!preg_match("/^[A-Za-z0-9!@#$%^&*()_.]*$/", $value)) {
                $errors[$key] = "Le champs contient des caractères non autorisés";
            }
        }

        Validator::notEmpty()->validate($data["firstName"]) || $errors["firstName"] = "Le prénom ne peut pas etre vide";
        Validator::notEmpty()->validate($data["lastName"]) || $errors["lastName"] = "Le nom ne peut pas etre vide";
        Validator::email()->validate($data["login"]) || $errors["login"] = "L'email n'est pas valide";
        Validator::allOf(
            Validator::notEmpty(),
            Validator::length(8),
            Validator::regex("/[A-Z]/"),
            Validator::regex("/[a-z]/"),
            Validator::regex("/[0-9]/"),
            Validator::regex("/[!@#$%^&*()_+]/")
        )->validate($data["password"]) || $errors["password"] = "Le mot de passe doit contenir au moins 8 caractères, une majuscule, une minuscule, un chiffre et un caractère spécial";

        if (empty($errors)) {
            flash((new Flash())->setMessage("L'utilisateur a bien été créé"));

            $user = (new User())
                ->setFirstName($data["firstName"])
                ->setLastName($data["lastName"])
                ->setProfilePicturePath($data["profilPicture"])
                ->setLogin($data["login"])
                ->setPasswordHash($data["password"])
                ->setDeleted(false);

            $entityManager->persist($user);
            $entityManager->flush();
            return redirect($response, "list");
        } else {
            flash((new Flash())
                ->setStatus(FlashStatus::warning)
                ->setMessage("Formulaire imcomplet, veuillez corriger les erreurs")
                ->setErrors($errors)
            );
            return redirect($response, "form");
        }
    });

    $app->get("/", function (Request $request, Response $response) {
        return render($response, "pages/home.twig");
    })->setName("home");

    $app->get("/base", function (Request $request, Response $response) {
        return render($response, "pages/base.twig");
    })->setName("base");

    $app->get("/login", function (Request $request, Response $response) {
        return render($response, "pages/login.twig");
    })->setName("login");

    $app->get("/user", function (Request $request, Response $response) {
        return render($response, "pages/user.twig");
    })->setName("user");

    $app->get("/user/wishlist", function (Request $request, Response $response) {
        return render($response, "pages/wishlist.twig");
    })->setName("wishlist");

    $app->get("/tos", function (Request $request, Response $response) {
        return render($response, "pages/tos.twig");
    })->setName("tos");

    $app->get("/company", function (Request $request, Response $response) {
        return render($response, "pages/company.twig");
    })->setName("company");

    $app->get("/companies", function (Request $request, Response $response) {
        return render($response, "pages/companies.twig");
    })->setName("companies");

    $app->get("/jobs", function (Request $request, Response $response) {
        return render($response, "pages/jobs.twig");
    })->setName("jobs");

    $app->get("/users", function (Request $request, Response $response) {
        return render($response, "pages/users.twig");
    })->setName("users");

    $app->get("/job", function (Request $request, Response $response) {
        return render($response, "pages/job.twig");
    })->setName("job");

    $app->get("/apply", function (Request $request, Response $response) {
        return render($response, "pages/apply_job.twig");
    })->setName("apply_job");

    $app->get("/company/rating", function (Request $request, Response $response) {
        return render($response, "pages/company_rating.twig");
    })->setName("company_rating");

    $app->get("/job/rating", function (Request $request, Response $response) {
        return render($response, "pages/job_rating.twig");
    })->setName("job_rating");

    $app->get("/wishlist", function (Request $request, Response $response) {
        return render($response, "pages/wishlist.twig");
    })->setName("wishlist");

    $app->get("/createjob", function (Request $request, Response $response) {
        return render($response, "pages/create_job.twig");
    })->setName("create_job");

    $app->get("/createuser/student", function (Request $request, Response $response) {
        return render($response, "pages/create_student.twig");
    })->setName("create_student");

    $app->get("/createuser/pilot", function (Request $request, Response $response) {
        return render($response, "pages/create_pilot.twig");
    })->setName("create_pilot");

    $app->get("/createcompany", function (Request $request, Response $response) {
        return render($response, "pages/create_company.twig");
    })->setName("create_company");
};