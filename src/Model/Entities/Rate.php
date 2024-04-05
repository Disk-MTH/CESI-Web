<?php

namespace stagify\Model\Entities;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;
use stagify\Model\Repositories\RateRepo;

#[Entity(repositoryClass: RateRepo::class), Table(name: "rate")]
class Rate
{
    #[Id, Column(type: "integer"), GeneratedValue(strategy: "AUTO")]
    private int $id;

    #[Column(type: "integer", nullable: false)]
    private int $grade;

    #[Column(type: "string", nullable: false)]
    private string $description;

    public function toArray(): array
    {
        return [
            "id" => $this->id,
            "grade" => $this->grade,
            "description" => $this->description
        ];
    }

    /*-------------------------------------------------- Getters --------------------------------------------------*/

    public function getId(): int
    {
        return $this->id;
    }

    public function getGrade(): int
    {
        return $this->grade;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    /*-------------------------------------------------- Setters --------------------------------------------------*/

    public function setGrade(int $grade): self
    {
        $this->grade = $grade;
        return $this;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;
        return $this;
    }
}