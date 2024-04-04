<?php

namespace stagify;

use Slim\App;
use stagify\Controllers\ApiController;
use stagify\Controllers\CompaniesController;
use stagify\Controllers\InternshipsController;
use stagify\Controllers\MiscController;
use stagify\Controllers\UsersController;

return function (App $app) {
    /* ---------------------------------------- Misc ----------------------------------------*/

    $app->get("/", [MiscController::class, "home"])->setName("home");

    $app->get("/tos", [MiscController::class, "tos"])->setName("tos");

    $app->get("/offline", [MiscController::class, "offline"])->setName("offline");

    $app->get("/login", [MiscController::class, "login"])->setName("login");

    $app->post("/login", [MiscController::class, "login"])->setName("login");

    $app->post("/logout", [MiscController::class, "logout"])->setName("logout");

    /* ---------------------------------------- Users ----------------------------------------*/

    $app->get("/users/{role}", [UsersController::class, "users"])->setName("users");

    $app->get("/user/{id}", [UsersController::class, "user"])->setName("user");

    $app->get("/user/{id}/wishlist", [UsersController::class, "wishlist"])->setName("wishlist");

    $app->get("/user/{id}/applications", [UsersController::class, "applications"])->setName("applications");

    $app->get("/create/user/{role}", [UsersController::class, "createUser"])->setName("create_user");

    $app->post("/create/user/{role}", [UsersController::class, "createUser"])->setName("create_user");

    /* ---------------------------------------- Companies ----------------------------------------*/

    $app->get("/companies", [CompaniesController::class, "companies"])->setName("companies");

    $app->get("/company/{id}/{location}", [CompaniesController::class, "company"])->setName("company");

    $app->get("/company/rating/{id}", [CompaniesController::class, "rating"])->setName("company_rating");

    $app->get("/create/company", [CompaniesController::class, "createCompany"])->setName("create_company");

    $app->post("/create/company", [CompaniesController::class, "createCompany"])->setName("create_company");

    $app->get("/company/{id}/{location}/rating", [CompaniesController::class, "rating"])->setName("company_rating");

    /* ---------------------------------------- Internships ----------------------------------------*/

    $app->get("/internships", [InternshipsController::class, "internships"])->setName("internships");

    $app->get("/internship", [InternshipsController::class, "internship"])->setName("internship");

    //TODO: internship -> id
    $app->get("/internship/apply", [InternshipsController::class, "apply"])->setName("apply_internship");

    $app->get("/create/internship", [InternshipsController::class, "createInternship"])->setName("create_internship");

    $app->post("/create/internship", [InternshipsController::class, "createInternship"])->setName("create_internship");

    $app->get("/internship/{id}", [InternshipsController::class, "internship"])->setName("internship");

    $app->get("/internship/{id}/rating", [InternshipsController::class, "rating"])->setName("internship_rating");

    $app->post("/internship/{id}/rating", [InternshipsController::class, "rating"])->setName("internship_rating");

    /* ---------------------------------------- API ----------------------------------------*/

    $app->get("/api/count/{type}", [ApiController::class, "count"]);

    $app->get("/api/internships/{page}", [ApiController::class, "internships"]);

    $app->get("/api/companies/{page}", [ApiController::class, "companies"]);

    $app->get("/api/users/{page}", [ApiController::class, "users"]);

    $app->get("/api/suggestions/companies/{pattern}", [ApiController::class, "companiesSuggestions"]);

    $app->get("/api/suggestions/promos/{pattern}", [ApiController::class, "promosSuggestions"]);

    $app->get("/api/suggestions/skills/{pattern}", [ApiController::class, "skillsSuggestions"]);

    $app->get("/api/suggestions/sectors/{pattern}", [ApiController::class, "activitySectorsSuggestions"]);

    $app->get("/api/suggestions/zip_codes/{pattern}", [ApiController::class, "zipCodesSuggestions"]);

    $app->get("/api/suggestions/cities/{pattern}", [ApiController::class, "citiesSuggestions"]);

    $app->get("/api/users/toggle_wish/{id}", [ApiController::class, "toggleWish"]);
};