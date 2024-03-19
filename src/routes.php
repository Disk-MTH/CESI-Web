<?php

namespace stagify;

use DateTime;
use Doctrine\ORM\EntityManager;
use Exception;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Log\LoggerInterface as Logger;
use Respect\Validation\Validator;
use Slim\App;
use stagify\Middlewares\ErrorsMiddleware;
use stagify\Middlewares\FlashMiddleware;
use stagify\Middlewares\OldDataMiddleware;
use stagify\Model\Entities\Session;
use stagify\Model\Entities\User;

function redirect(Response $response, string $url): Response
{
    return $response->withStatus(302)->withHeader("Location", $url);
}

/**
 * @throws Exception
 */
function render(Response $response, string $template, array $data = []): Response
{
    global $twig;
    global $entityManager;

    $sessionRepo = $entityManager->getRepository(Session::class);
    $session = $sessionRepo->findOneBy(["token" => $_COOKIE["session"] ?? ""]);

    if ($session != null) {
        if ($session->getLastActivity() < new DateTime("-" . Session::$duration)) {
            $session = null;
            Session::logOut();
            FlashMiddleware::flash("warning", "Session expirée, veuillez vous reconnecter");
        } else {
            $session->setLastActivity(new DateTime());
            $entityManager->flush();
            Session::logIn($session);
        }
    }

    if ($session != null && $template !== "pages/login.twig") {
        $data["user"] = $entityManager->getRepository(User::class)->findOneBy(["id" => $_SESSION["user"]]);

        global $logger;
        $logger->warning(($data["user"])->getPromos()[0]->getYear());
    }

    if ($session == null && $template !== "pages/login.twig") {
        return redirect($response, "login");
    } else if ($session != null && $template === "pages/login.twig") {
        return redirect($response, "/");
    }
    return $twig->render($response, $template, $data);
}

return function (App $app, Logger $logger, EntityManager $entityManager) {
    $app->get("/", function (Request $request, Response $response) use ($entityManager, $logger) {
        return render($response, "pages/home.twig");
    })->setName("home");

    $app->get("/login", function (Request $request, Response $response) {
        return render($response, "pages/login.twig");
    })->setName("login");

    $app->post("/login", function (Request $request, Response $response) use ($entityManager) {
        $data = $request->getParsedBody();
        $errors = OldDataMiddleware::validate($data);
        $fail = false;

        Validator::email()->validate($data["login"]) || $errors["login"] = "L'email n'est pas valide";
        Validator::notEmpty()->validate($data["password"]) || $errors["password"] = "Le mot de passe ne peut pas être vide";

        if (empty($errors)) {
            $userRepo = $entityManager->getRepository(User::class);
            $user = $userRepo->findOneBy(["login" => $data["login"], "passwordHash" => hash("sha512", $data["password"])]);

            if ($user != null) {
                $session = (new Session())
                    ->setLastActivity(new DateTime())
                    ->setToken(hash("sha512", random_bytes(10)))
                    ->setUser($user);
                $entityManager->persist($session);
                $entityManager->flush();

                Session::logIn($session);
                FlashMiddleware::flash("success", "Connexion réussie");
            } else {
                $fail = true;
                FlashMiddleware::flash("error", "Identifiants incorrects, veuillez réessayer");
            }
        } else {
            $fail = true;
        }

        if ($fail) {
            ErrorsMiddleware::error($errors);
            return redirect($response, "login");
        }
        return redirect($response, "/");
    })->setName("login");

    $app->post("/logout", function (Request $request, Response $response) use ($entityManager) {
        Session::logOut();
        FlashMiddleware::flash("success", "Déconnexion réussie");
        return redirect($response, "login");
    })->setName("logout");

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

    $app->get("/pilots", function (Request $request, Response $response) {
        return render($response, "pages/pilots.twig");
    })->setName("pilots");
};