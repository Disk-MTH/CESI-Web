<?php

namespace stagify\Model\Entities;

use DateTime;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;

#[Entity, Table(name: "session")]
class Session
{
    public static string $duration = "1 day";

    #[Id, Column(type: "integer"), GeneratedValue(strategy: "AUTO")]
    private int $id;

    #[Column(type: "datetime", nullable: false)]
    private DateTime $lastActivity;

    #[Column(type: "string", nullable: false)]
    private string $token;

    #[ManyToOne(targetEntity: User::class)]
    private User $user;

    /*-------------------------------------------------- Getters --------------------------------------------------*/

    public function getId(): int
    {
        return $this->id;
    }

    public function getLastActivity(): DateTime
    {
        return $this->lastActivity;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    /*-------------------------------------------------- Setters --------------------------------------------------*/

    public function setLastActivity(DateTime $lastActivity): self
    {
        $this->lastActivity = $lastActivity;
        return $this;
    }

    public function setToken(string $token): self
    {
        $this->token = $token;
        return $this;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;
        return $this;
    }
}