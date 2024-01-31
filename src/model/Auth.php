<?php

namespace stagify\model;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;

#[Entity, Table(name: "auth")]
class Auth
{
    #[Id, Column(name: "auth_id", type: "integer"), GeneratedValue(strategy: "AUTO")]
    private int $id;

    #[Column(name: "login", type: "string", nullable: false)]
    private string $login;

    #[Column(name: "password_hash", type: "string", nullable: false)]
    private string $passwordHash;

    public function __construct(string $name, string $email)
    {
        $this->login = $name;
        $this->passwordHash = $email;
    }

    public function getLogin(): string
    {
        return $this->login;
    }

    public function getPasswordHash(): string
    {
        return $this->passwordHash;
    }
}