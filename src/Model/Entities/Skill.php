<?php

namespace stagify\Model\Entities;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;
use stagify\Model\Repositories\SkillRepo;

#[Entity(repositoryClass: SkillRepo::class), Table(name: "skill")]
class Skill
{
    #[Id, Column(type: "integer"), GeneratedValue(strategy: "AUTO")]
    private int $id;

    #[Column(type: "string", nullable: false)]
    private string $name;

    /*-------------------------------------------------- Getters --------------------------------------------------*/

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    /*-------------------------------------------------- Setters --------------------------------------------------*/

    public function setName(string $name): void
    {
        $this->name = $name;
    }
}