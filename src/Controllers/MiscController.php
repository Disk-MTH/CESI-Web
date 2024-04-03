<?php

namespace stagify\Controllers;

use DateTime;
use Doctrine\ORM\EntityRepository;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Respect\Validation\Validator;
use stagify\Middlewares\ErrorsMiddleware;
use stagify\Middlewares\FlashMiddleware;
use stagify\Middlewares\SessionMiddleware;
use stagify\Model\Entities\Session;
use stagify\Model\Entities\User;
use stagify\Model\Repositories\UserRepo;
use Throwable;

class MiscController extends Controller
{
    /** @var UserRepo $userRepo */
    private EntityRepository $userRepo;

    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
        $this->userRepo = $this->entityManager->getRepository(User::class);
    }

    function home(Request $request, Response $response): Response
    {
        return $this->render($response, "pages/home.twig");
    }

    /** @throws Throwable */
    function login(Request $request, Response $response): Response
    {
        if ($request->getMethod() === "GET") {
            return $this->render($response, "pages/login.twig");
        }

        if ($request->getMethod() === "POST") {
            $data = $request->getParsedBody();
            $errors = ErrorsMiddleware::validate($data);
            $fail = false;

            Validator::email()->validate($data["login"]) || $errors["login"] = "L'email n'est pas valide";
            Validator::notEmpty()->validate($data["password"]) || $errors["password"] = "Le mot de passe ne peut pas être vide";

            if (empty($errors)) {
                $user = $this->userRepo->findOneBy(["login" => $data["login"], "passwordHash" => hash("sha512", $data["password"])]);

                if ($user != null) {
                    $persist = isset($data["persist"]);
                    $session = (new Session())
                        ->setLastActivity(new DateTime())
                        ->setToken(hash("sha512", random_bytes(10)))
                        ->setPersist($persist)
                        ->setUser($user);
                    $this->entityManager->persist($session);
                    $this->entityManager->flush();

                    SessionMiddleware::logIn($session, $persist);
                    FlashMiddleware::flash("success", "Connexion réussie");
                } else {
                    $fail = true;
                    FlashMiddleware::flash("error", "Identifiants incorrects, veuillez réessayer");
                }
            } else $fail = true;

            if ($fail) {
                ErrorsMiddleware::error($errors);
                return $this->redirect($response, "/login");
            }
            return $this->redirect($response, "/");
        }

        return $this->redirect($response, "/login");
    }

    function logout(Request $request, Response $response): Response
    {
        SessionMiddleware::logOut();
        FlashMiddleware::flash("success", "Déconnexion réussie");
        return $this->redirect($response, "/login");
    }

    function tos(Request $request, Response $response): Response
    {
        return $this->render($response, "pages/tos.twig");
    }

    function offline(Request $request, Response $response): Response
    {
        return $this->render($response, "pages/offline.twig");
    }
}