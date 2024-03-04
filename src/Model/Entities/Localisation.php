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

#[Entity, Table(name: "localisation")]
class Localisation
{
    #[Id, Column(type: "integer"), GeneratedValue(strategy: "AUTO")]
    private int $id;

    #[Column(type: "string", nullable: false)]
    private string $country;

    #[Column(type: "string", nullable: false)]
    private string $city;

    #[Column(type: "integer", nullable: false)]
    private int $zipCode;

    #[Column(type: "string", nullable: false)]
    private string $street;

    #[OneToMany(mappedBy: "localisation", targetEntity: Campus::class)]
    private Collection $campuses;

    public function __construct()
    {
        $this->campuses = new ArrayCollection();
    }

    /*-------------------------------------------------- Getters --------------------------------------------------*/

    public function getId(): int
    {
        return $this->id;
    }

    public function getCountry(): string
    {
        return $this->country;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function getZipCode(): int
    {
        return $this->zipCode;
    }

    public function getStreet(): string
    {
        return $this->street;
    }

    public function getCampuses(): Collection
    {
        return $this->campuses;
    }

    /*-------------------------------------------------- Setters --------------------------------------------------*/

    public function setCountry(string $country): self
    {
        $this->country = $country;
        return $this;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;
        return $this;
    }

    public function setZipCode(int $zipCode): self
    {
        $this->zipCode = $zipCode;
        return $this;
    }

    public function setStreet(string $street): self
    {
        $this->street = $street;
        return $this;
    }

    public function addCampus(Campus $campus): self
    {
        if (!$this->campuses->contains($campus)) {
            $this->campuses[] = $campus;
            $campus->setLocalisation($this);
        }
        return $this;
    }

    public function removeCampus(Campus $campus): self
    {
        if ($this->campuses->removeElement($campus)) {
            if ($campus->getLocalisation() === $this) {
                $campus->setLocalisation(null);
            }
        }
        return $this;
    }
}