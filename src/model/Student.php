<?php

namespace stagify\model;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToOne;
use Doctrine\ORM\Mapping\Table;

#[Entity, Table(name: "student")]
class Student
{
    #[Id, Column(type: "integer"), GeneratedValue(strategy: "AUTO")]
    private int $id;

    #[ManyToOne(targetEntity: User::class)]
    private User $user;

    public function __construct(string $firstName, string $lastName, string $login, string $passwordHash)
    {
        $this->user = new User($firstName, $lastName, $login, $passwordHash, new Campus("Paris"));
    }

    public function getFirstName(): string
    {
        return $this->user->getFirstName();
    }

    public function getLastName(): string
    {
        return $this->user->getLastName();
    }

    public function getLogin(): string
    {
        return $this->user->getAuth()->getLogin();
    }

    public function getPasswordHash(): string
    {
        return $this->user->getAuth()->getPasswordHash();
    }
}