<?php

namespace stagify\Model\Entities;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Table;

#[Entity, Table(name: "campus")]
class Campus
{
    #[Id, Column(type: "integer"), GeneratedValue(strategy: "AUTO")]
    private int $id;

    #[Column(type: "string", nullable: false)]
    private string $name;

    #[ManyToOne(inversedBy: "campuses")]
    private ?Localisation $localisation;

    #[OneToMany(mappedBy: "campus", targetEntity: Promo::class)]
    private Collection $promos;

    public function __construct()
    {
        $this->promos = new ArrayCollection();
    }

    /*-------------------------------------------------- Getters --------------------------------------------------*/

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getLocalisation(): ?Localisation
    {
        return $this->localisation;
    }

    public function getPromos(): Collection
    {
        return $this->promos;
    }

    /*-------------------------------------------------- Setters --------------------------------------------------*/

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function setLocalisation(?Localisation $localisation): self
    {
        $this->localisation = $localisation;
        return $this;
    }

    public function addPromo(Promo $promo): self
    {
        if (!$this->promos->contains($promo)) {
            $this->promos[] = $promo;
        }
        return $this;
    }

    public function removePromo(Promo $promo): self
    {
        if ($this->promos->removeElement($promo)) {
            if ($promo->getCampus() === $this) {
                $promo->setCampus(null);
            }
        }
        return $this;
    }
}