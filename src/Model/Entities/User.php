<?php

namespace stagify\Model\Entities;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;

#[Entity, Table(name: "user")]
class User
{
    #[Id, Column(type: "integer"), GeneratedValue(strategy: "AUTO")]
    private int $id;

    #[Column(type: "string", nullable: false)]
    private string $firstName;

    #[Column(type: "string", nullable: false)]
    private string $lastName;

    #[Column(type: "string", nullable: false)]
    private string $profilePicturePath;

    #[Column(type: "string", nullable: false)]
    private string $login;

    #[Column(type: "string", nullable: false)]
    private string $passwordHash;

    #[Column(type: "boolean", nullable: false)]
    private bool $deleted;

    #[ManyToOne(targetEntity: Promo::class)]
    #[JoinColumn(name: "promo_id", referencedColumnName: "id")]
    private ?Promo $promo;

    public function __construct()
    {
    }

    /*-------------------------------------------------- Getters --------------------------------------------------*/

    public function getId(): int
    {
        return $this->id;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getProfilePicturePath(): string
    {
        return $this->profilePicturePath;
    }

    public function getLogin(): string
    {
        return $this->login;
    }

    public function getPasswordHash(): string
    {
        return $this->passwordHash;
    }

    public function getDeleted(): bool
    {
        return $this->deleted;
    }

    public function getPromo(): ?Promo
    {
        return $this->promo;
    }

    /*-------------------------------------------------- Setters --------------------------------------------------*/

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;
        return $this;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;
        return $this;
    }

    public function setProfilePicturePath(string $profilePicturePath): self
    {
        $this->profilePicturePath = $profilePicturePath;
        return $this;
    }

    public function setLogin(string $login): self
    {
        $this->login = $login;
        return $this;
    }

    public function setPasswordHash(string $passwordHash): self
    {
        $this->passwordHash = $passwordHash;
        return $this;
    }

    public function setDeleted(bool $deleted): self
    {
        $this->deleted = $deleted;
        return $this;
    }

    public function setPromo(?Promo $promo): self
    {
        $this->promo = $promo;
        return $this;
    }
}