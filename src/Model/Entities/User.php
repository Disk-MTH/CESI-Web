<?php

namespace stagify\Model\Entities;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;

#[Entity, Table(name: "user")]
class User
{
    #[Id, Column(type: "integer"), GeneratedValue(strategy: "AUTO")]
    private int $id;

    #[Column(name: "first_name", type: "string", nullable: false)]
    private string $firstName;

    #[Column(name: "last_name", type: "string", nullable: false)]
    private string $lastName;

    #[ManyToOne(targetEntity: Auth::class)]
    private Auth $auth;

    #[ManyToOne(targetEntity: Campus::class)]
    private Campus $campus;

    public function __construct(string $firstName, string $lastName, string $login, string $passwordHash, Campus $campus)
    {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->auth = new Auth($login, $passwordHash);
        $this->campus = $campus;
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

    public function getCampus(): Campus
    {
        return $this->campus;
    }
}