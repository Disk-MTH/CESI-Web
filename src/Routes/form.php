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
use stagify\Model\Entities\ActivitySector;
use stagify\Model\Entities\Company;
use stagify\Model\Entities\Location;
use function stagify\moveUploadedFile;
use function stagify\redirect;
use function stagify\render;


return function (App $app, Logger $logger, Twig $twig, EntityManager $entityManager, $fileDirectory) {
    $app->get("/create-company", function (Request $request, Response $response) {
        return render($response, "pages/create_company.twig");
    })->setName("create_company");

    $app->post("/create-company", function (Request $request, Response $response) use ($entityManager, $logger, $fileDirectory) {
        $data = $request->getParsedBody();
        $uploadedFiles = $request->getUploadedFiles();

        $logger->debug("Creating company with data: " . json_encode($data));
        $logger->debug("Uploaded files: " . print_r($uploadedFiles, true));

        $errors = OldDataMiddleware::validate($data);

        Validator::notEmpty()->validate($data["name"]) || $errors["name"] = "Le nom ne peut pas être vide";
        Validator::notEmpty()->validate($uploadedFiles["logo"]) || $errors["logo"] = "Le logo ne peut pas être vide";
        Validator::notEmpty()->validate($data["sector"]) || $errors["sector"] = "Le secteur ne peut pas être vide";
        Validator::intType()->validate($data["zipCode"]) || $errors["zipCode"] = "Le code postal doit être un nombre";
        Validator::notEmpty()->validate($data["city"]) || $errors["city"] = "La ville ne peut pas être vide";
        Validator::intType()->validate($data["employees"]) || $errors["employees"] = "Le nombre d'employés doit être un nombre";
        Validator::url()->validate($data["website"]) || $errors["website"] = "Le site web n'est pas valide";

        if (empty($errors)) {
            $uploadedFile = $uploadedFiles["logo"];

            $Company = new Company();
            $Company->setName($data["name"]);
            $Company->setWebsite($data["website"]);
            $Company->setEmployeeCount($data["employees"]);

            $filename = moveUploadedFile($fileDirectory, $uploadedFile);
            $Company->setLogoPath($filename);

            $ActivitySector = new ActivitySector();
            $ActivitySector->setName($data["sector"]);

            $Company->setActivitySector($ActivitySector);

            $Location = new Location();
            $Location->setZipCode($data["zipCode"]);
            $Location->setCity($data["city"]);

            $Company->addLocation($Location);

            $entityManager->persist($Company);
            $entityManager->flush();

            FlashMiddleware::flash("success", "Entreprise créée avec succès");
            return redirect($response, "/");
        }

        ErrorsMiddleware::error($errors);
        return redirect($response, "create-company");
    })->setName("create_company");

    $app->get("/create-job", function (Request $request, Response $response) {
        return render($response, "pages/create_job.twig");
    })->setName("create_job");

    $app->get("/create-user/student", function (Request $request, Response $response) {
        return render($response, "pages/create_student.twig");
    })->setName("create_student");

    $app->get("/create-user/pilot", function (Request $request, Response $response) {
        return render($response, "pages/create_pilot.twig");
    })->setName("create_pilot");

    //TODO: internship -> id
    $app->get("/internship/apply", function (Request $request, Response $response) {
        return render($response, "pages/apply_job.twig");
    })->setName("apply_job");
};