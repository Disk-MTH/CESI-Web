<?php

namespace stagify\Model\Entities;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;
use stagify\Model\Repositories\ApplicationRepo;

#[Entity(repositoryClass: ApplicationRepo::class), Table(name: "application")]
class Application
{
    #[Id, Column(type: "integer"), GeneratedValue(strategy: "AUTO")]
    private int $id;

    #[Column(type: "string", nullable: false)]
    private string $cvFile;

    #[Column(type: "string", nullable: false)]
    private string $coverLetterFile;

    #[Column(type: "boolean", nullable: false)]
    private bool $accepted;

    #[JoinColumn(referencedColumnName: "id")]
    #[ManyToOne(targetEntity: Internship::class)]
    private Internship $internship;

    #[JoinColumn(referencedColumnName: "id")]
    #[ManyToOne(targetEntity: User::class)]
    private User $user;

    /*-------------------------------------------------- Getters --------------------------------------------------*/

    public function getId(): int
    {
        return $this->id;
    }

    public function getCvFile(): string
    {
        return $this->cvFile;
    }

    public function getCoverLetterFile(): string
    {
        return $this->coverLetterFile;
    }

    public function getAccepted(): bool
    {
        return $this->accepted;
    }

    public function getInternship(): Internship
    {
        return $this->internship;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    /*-------------------------------------------------- Setters --------------------------------------------------*/

    public function setCvFile(string $cvFile): self
    {
        $this->cvFile = $cvFile;
        return $this;
    }

    public function setCoverLetterFile(string $coverLetterFile): self
    {
        $this->coverLetterFile = $coverLetterFile;
        return $this;
    }

    public function setAccepted(bool $accepted): self
    {
        $this->accepted = $accepted;
        return $this;
    }

    public function setInternship(Internship $internship): self
    {
        $this->internship = $internship;
        return $this;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;
        return $this;
    }
}