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
use stagify\Model\Entities\Internship;
use stagify\Model\Repositories\InternshipRepo;

class InternshipsController extends Controller
{
    /** @var InternshipRepo $internshipRepo */
    private EntityRepository $internshipRepo;

    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
        $this->internshipRepo = $this->entityManager->getRepository(Internship::class);
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
            var_dump($request->getParsedBody());
            $data = $request->getParsedBody();
            $errors = ErrorsMiddleware::validate($data);

            $skills = [];
            $promos = [];

            foreach ($data as $key => $value) {
                if (str_starts_with($key, "suggestion@skills_")) $skills[] = $value;
                if (str_starts_with($key, "suggestion@promos_")) $promos[] = $value;
            }

            Validator::notEmpty()->validate($data["companyField"]) || $errors["company"] = "L'entreprise ne peut pas être vide";
            Validator::notEmpty()->validate($data["title"]) || $data["title"] = "Le titre ne peut pas être vide";
            Validator::date()->validate($data["startDate"]) || $errors["startDate"] = "La date de début n'est pas valide";
            Validator::date()->validate($data["endDate"]) || $errors["endDate"] = "La date de fin n'est pas valide";
            Validator::intType()->validate($data["duration"]) || $errors["duration"] = "La durée doit être un nombre";
            Validator::intType()->validate($data["lowSalary"]) || $errors["lowSalary"] = "Le salaire minimum doit être un nombre";
            Validator::intType()->validate($data["highSalary"]) || $errors["highSalary"] = "Le salaire maximum doit être un nombre";
            Validator::notEmpty()->validate($data["description"]) || $errors["description"] = "La description ne peut pas être vide";
            Validator::notEmpty()->validate($skills) || $errors["skills"] = "Les compétences ne peuvent pas être vides";
            Validator::notEmpty()->validate($promos) || $errors["promos"] = "Les promotions ne peuvent pas être vides";

            if (empty($errors)) {
                /*$uploadedFile = $uploadedFiles["logo"];

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

                FlashMiddleware::flash("success", "Entreprise créée avec succès");*/
                FlashMiddleware::flash("success", "L'offre de stage a bien été créée.");
            } else {
                FlashMiddleware::flash("error", "Une erreur est survenue lors de la création de l'offre de stage.");
                ErrorsMiddleware::error($errors);
            }
//            if ($this->internshipRepo->create($request->getParsedBody())) FlashMiddleware::flash("success", "L'offre de stage a bien été créée.");
//            else FlashMiddleware::flash("error", "Une erreur est survenue lors de la création de l'offre de stage.");
        }

        return $this->redirect($response, "/create/internship");
    }
}