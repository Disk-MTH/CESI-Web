<?php

namespace stagify\Model\Entities;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Table;
use stagify\Model\Repositories\LocationRepo;

#[Entity(repositoryClass: LocationRepo::class), Table(name: "location")]
class Location
{
    #[Id, Column(type: "integer"), GeneratedValue(strategy: "AUTO")]
    private int $id;

    #[Column(type: "integer", nullable: false)]
    private int $zipCode;

    #[Column(type: "string", nullable: false)]
    private string $city;

    public function toArray() : array
    {
        return [
            "id" => $this->id,
            "zipCode" => $this->zipCode,
            "city" => $this->city
        ];
    }

    /*-------------------------------------------------- Getters --------------------------------------------------*/

    public function getId(): int
    {
        return $this->id;
    }

    public function getZipCode(): int
    {
        return $this->zipCode;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    /*-------------------------------------------------- Setters --------------------------------------------------*/

    public function setZipCode(int $zipCode): self
    {
        $this->zipCode = $zipCode;
        return $this;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;
        return $this;
    }
}