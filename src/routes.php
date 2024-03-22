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
use Slim\Views\Twig;
use stagify\Middlewares\ErrorsMiddleware;
use stagify\Middlewares\FlashMiddleware;
use stagify\Middlewares\OldDataMiddleware;
use stagify\Model\Entities\InternshipOffer;
use stagify\Model\Entities\Session;
use stagify\Model\Entities\User;
use stagify\Model\Repositories\InternshipOfferRepo;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

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
        if ($template !== "pages/login.twig") {
            $data["user"] = $entityManager->getRepository(User::class)->findOneBy(["id" => $_SESSION["user"]]);
        }

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

    if ($session == null && $template !== "pages/login.twig") {
        return redirect($response, "login");
    } else if ($session != null && $template === "pages/login.twig") {
        return redirect($response, "/");
    }

    return $twig->render($response, $template, $data);
}

return function (App $app, Logger $logger, Twig $twig, EntityManager $entityManager) {
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

    $app->get("/internships", function (Request $request, Response $response) use ($entityManager) {
        return render($response, "pages/internships.twig");
    })->setName("internships");

    /*-------------------------------------------------- Endpoints --------------------------------------------------*/

    $app->get("/internships/{page}", function (Request $request, Response $response, array $args) use ($entityManager, $logger) {
        $page = $args["page"];
        $count = $request->getQueryParams()["count"] ?? 4;

        if ($page < 0) {
            $response->withStatus(404)->getBody()->write(json_encode(["error" => "Page index out of range"]));
            return $response;
        }

        /** @var InternshipOfferRepo $internshipRepo */
        $internshipRepo = $entityManager->getRepository(InternshipOffer::class);
        $internships = $internshipRepo->getInternshipOffers($page, $count);
        $internships = array_map(function($internship) {
            return $internship->toArray();
        }, $internships);

        $response->getBody()->write(json_encode($internships));
        return $response->withHeader("Content-Type", "application/json");
    });

    // $internshipRepo = $entityManager->getRepository(InternshipOffer::class);
    // $internships = $internshipRepo->findAll();


    /*
     *
     *
     *
     *
     *
     *
     */


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
        return render($response, "pages/internships.twig");
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

    $app->post("/create-company", function (Request $request, Response $response) use ($entityManager, $logger) {
        $data = $request->getParsedBody();
        $uploadedFiles = $request->getUploadedFiles();
        $logger->debug("Creating company with data: " . json_encode($data));
        $errors = OldDataMiddleware::validate($data);
        $fail = false;

        $logger->debug("Uploaded files: " . print_r($uploadedFiles, true));

        Validator::notEmpty()->validate($data["name"]) || $errors["name"] = "Le nom ne peut pas être vide";
        Validator::notEmpty()->validate($uploadedFiles["logo"]) || $errors["logo"] = "Le logo ne peut pas être vide";
        Validator::notEmpty()->validate($data["sector"]) || $errors["sector"] = "Le secteur ne peut pas être vide";
        Validator::notEmpty()->validate($data["zipCode"]) || $errors["zipCode"] = "Le code postal ne peut pas être vide";
        Validator::notEmpty()->validate($data["city"]) || $errors["city"] = "La ville ne peut pas être vide";
        Validator::notEmpty()->validate($data["employees"]) || $errors["employees"] = "Le nombre d'employés ne peut pas être vide";
        Validator::notEmpty()->validate($data["website"]) || $errors["website"] = "Le site web ne peut pas être vide";

        if (empty($errors)) {
            $CompanyRepo = $entityManager->getRepository(Company::class);
            $Company = new Company();
            $Company->setName($data["name"]);
            $Company->setWebsite($data["website"]);
            $Company->setEmployeeCount($data["employees"]);

            if (isset($uploadedFiles['logo']) && $uploadedFiles['logo']->getError() === UPLOAD_ERR_OK) {
                $Company->setLogoPath($uploadedFiles["logo"]);
            } else {
                // Handle the case where no file was uploaded
                $logger->error("No file was uploaded for 'logo'");
            }

            $Company->setActivitySector($data["sector"]);

            $Location = new Location();
            $Location->setZipCode($data["zipCode"]);
            $Location->setCity($data["city"]);

            $Company->addLocation($Location);

            $entityManager->persist($Company);
            $entityManager->flush();

            FlashMiddleware::flash("success", "Entreprise créée avec succès");
            return redirect($response, "companies");
        } else {
            $fail = true;
        }
        if ($fail) {
            ErrorsMiddleware::error($errors);
            return redirect($response, "createcompany");
        }
        return redirect($response, "companies");
    })->setName("create_company");

    $app->get("/pilots", function (Request $request, Response $response) {
        return render($response, "pages/pilots.twig");
    })->setName("pilots");

    $app->get("/offline", function (Request $request, Response $response) use ($twig) {
        return $twig->render($response, "pages/offline.twig");
    });
};