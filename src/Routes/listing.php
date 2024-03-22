<?php

use Doctrine\ORM\EntityManager;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Log\LoggerInterface as Logger;
use Slim\App;
use Slim\Views\Twig;
use stagify\Model\Entities\InternshipOffer;
use stagify\Model\Repositories\InternshipOfferRepo;
use function stagify\render;

return function (App $app, Logger $logger, Twig $twig, EntityManager $entityManager, string $fileDirectory) {
    $app->get("/internships", function (Request $request, Response $response) use ($entityManager) {
        $total = $request->getQueryParams()["count"] ?? false;

        if ($total) {
            /** @var InternshipOfferRepo $internshipRepo */
            $internshipRepo = $entityManager->getRepository(InternshipOffer::class);
            $response->getBody()->write(json_encode(["count" => $internshipRepo->count([])]));
            return $response->withHeader("Content-Type", "application/json");
        }

        return render($response, "pages/internships.twig");
    })->setName("internships");

    $app->get("/companies", function (Request $request, Response $response) {
        return render($response, "pages/companies.twig");
    })->setName("companies");

    $app->get("/users", function (Request $request, Response $response) {
        return render($response, "pages/users.twig");
    })->setName("users");

    //TODO: user -> id
    $app->get("/user/wishlist", function (Request $request, Response $response) {
        return render($response, "pages/wishlist.twig");
    })->setName("wishlist");

    $app->get("/pilots", function (Request $request, Response $response) {
        return render($response, "pages/pilots.twig");
    })->setName("pilots");

    //TODO: company -> id
    $app->get("/company/rating", function (Request $request, Response $response) {
        return render($response, "pages/company_rating.twig");
    })->setName("company_rating");

    //TODO: internship -> id
    $app->get("/internship/rating", function (Request $request, Response $response) {
        return render($response, "pages/internships_rating.twig");
    })->setName("internship_rating");
};