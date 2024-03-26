<?php

use Doctrine\ORM\EntityManager;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Log\LoggerInterface as Logger;
use Respect\Validation\Validator;
use Slim\App;
use Slim\Views\Twig;
use stagify\Middlewares\ErrorsMiddleware;
use stagify\Middlewares\FlashMiddleware;
use stagify\Middlewares\OldDataMiddleware;
use stagify\Model\Entities\Session;
use stagify\Model\Entities\User;
use function stagify\redirect;
use function stagify\render;

return function (App $app, Logger $logger, Twig $twig, EntityManager $entityManager) {
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
};