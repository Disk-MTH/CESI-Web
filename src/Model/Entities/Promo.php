<?php

namespace stagify\Model\Entities;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\InverseJoinColumn;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Table;
use stagify\Model\Repositories\PromoRepo;

#[Entity(repositoryClass: PromoRepo::class), Table(name: "promo")]
class Promo
{
    #[Id, Column(type: "integer"), GeneratedValue(strategy: "AUTO")]
    private int $id;

    #[Column(type: "integer", nullable: false)]
    private int $year;

    #[Column(type: "string", nullable: false)]
    private string $type;

    #[Column(type: "string", nullable: false)]
    private string $school;

    #[JoinTable]
    #[JoinColumn(referencedColumnName: "id")]
    #[InverseJoinColumn(referencedColumnName: "id")]
    #[ManyToMany(targetEntity: Location::class)]
    private Collection $locations;

    public function __construct()
    {
        $this->locations = new ArrayCollection();
    }

    /*-------------------------------------------------- Getters --------------------------------------------------*/

    public function getId(): int
    {
        return $this->id;
    }

    public function getYear(): int
    {
        return $this->year;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getSchool(): string
    {
        return $this->school;
    }

    public function getLocations(): Collection
    {
        return $this->locations;
    }

    /*-------------------------------------------------- Setters --------------------------------------------------*/

    public function setYear(int $year): self
    {
        $this->year = $year;
        return $this;
    }

    public function setType(string $type): self
    {
        $this->type = $type;
        return $this;
    }

    public function setSchool(string $school): self
    {
        $this->school = $school;
        return $this;
    }

    public function addLocation(Location $location): self
    {
        $this->locations->add($location);
        return $this;
    }

    public function removeLocation(Location $location): self
    {
        $this->locations->removeElement($location);
        return $this;
    }
}