<?php

namespace stagify\Model\Entities;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;

#[Entity, Table(name: "application")]
class Application
{
    #[Id, Column(type: "integer"), GeneratedValue(strategy: "AUTO")]
    private int $id;

    #[Column(type: "string", nullable: false)]
    private string $cvPath;

    #[Column(type: "string", nullable: false)]
    private string $coverLetterPath;

    #[Column(type: "boolean", nullable: false)]
    private bool $accepted;

    #[JoinColumn(referencedColumnName: "id")]
    #[ManyToOne(targetEntity: InternshipOffer::class)]
    private InternshipOffer $internshipOffer;

    #[JoinColumn(referencedColumnName: "id")]
    #[ManyToOne(targetEntity: User::class)]
    private User $user;

    /*-------------------------------------------------- Getters --------------------------------------------------*/

    public function getId(): int
    {
        return $this->id;
    }

    public function getCvPath(): string
    {
        return $this->cvPath;
    }

    public function getCoverLetterPath(): string
    {
        return $this->coverLetterPath;
    }

    public function getAccepted(): bool
    {
        return $this->accepted;
    }

    public function getInternshipOffer(): InternshipOffer
    {
        return $this->internshipOffer;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    /*-------------------------------------------------- Setters --------------------------------------------------*/

    public function setCvPath(string $cvPath): self
    {
        $this->cvPath = $cvPath;
        return $this;
    }

    public function setCoverLetterPath(string $coverLetterPath): self
    {
        $this->coverLetterPath = $coverLetterPath;
        return $this;
    }

    public function setAccepted(bool $accepted): self
    {
        $this->accepted = $accepted;
        return $this;
    }

    public function setInternshipOffer(InternshipOffer $internshipOffer): self
    {
        $this->internshipOffer = $internshipOffer;
        return $this;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;
        return $this;
    }
}