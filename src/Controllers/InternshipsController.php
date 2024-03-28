<?php

namespace stagify\Controllers;

use Doctrine\ORM\EntityRepository;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
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

        if ($total) {
            $response->getBody()->write(json_encode(["count" => $this->internshipRepo->count([])]));
            return $response->withHeader("Content-Type", "application/json");
        }

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
        return $this->render($response, "pages/create_internship.twig");
    }
}