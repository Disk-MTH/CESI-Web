<?php

namespace stagify\Model\Entities;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;
use stagify\Model\Repositories\ActivitySectorRepo;

#[Entity(repositoryClass: ActivitySectorRepo::class), Table(name: "activity_sector")]
class ActivitySector
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

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }
}