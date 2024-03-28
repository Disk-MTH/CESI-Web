<?php

namespace stagify;

use Slim\Factory\AppFactory;

require __DIR__ . "/../vendor/autoload.php";

session_start();

$container = require __DIR__ . "/../src/dependencies.php";
$container = $container();

AppFactory::setContainer($container);
$app = AppFactory::create();

$middlewares = require __DIR__ . "/../src/middlewares.php";
$middlewares($app, $container);

$routes = require __DIR__ . "/../src/routes.php";
$routes($app);

$app->run();

/*$app->get("/list", function (Request $request, Response $response) use ($entityManager) {
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
    });*/