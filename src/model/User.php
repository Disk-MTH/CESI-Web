<?php

namespace stagify\model;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\OneToOne;
use Doctrine\ORM\Mapping\Table;

#[Entity, Table(name: "user")]
class User
{
    #[Id, Column(name: "user_id", type: "integer"), GeneratedValue(strategy: "AUTO")]
    private int $id;

    #[Column(name: "first_name", type: "string", nullable: false)]
    private string $firstName;

    #[Column(name: "last_name", type: "string", nullable: false)]
    private string $lastName;

    #[ManyToOne(targetEntity: Auth::class), JoinColumn(name: "user_id", referencedColumnName: "auth_id")]
    private Auth $auth;

    //#[ManyToOne(targetEntity: Campus::class), JoinColumn(name: "user_id", referencedColumnName: "id")]
    //private Campus $campus;

    public function __construct(string $firstName, string $lastName, string $login, string $passwordHash)
    {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->auth = new Auth($login, $passwordHash);
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getAuth(): Auth
    {
        return $this->auth;
    }
}