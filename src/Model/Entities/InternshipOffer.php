<?php

namespace stagify\Model\Entities;

use DateTime;
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
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;
use stagify\Model\Repositories\InternshipOfferRepo;

#[Entity(repositoryClass: InternshipOfferRepo::class), Table(name: "internship_offer")]
class InternshipOffer
{
    #[Id, Column(type: "integer"), GeneratedValue(strategy: "AUTO")]
    private int $id;

    #[Column(type: "date", nullable: false)]
    private DateTime $startDate;

    #[Column(type: "date", nullable: false)]
    private DateTime $endDate;

    #[Column(type: "integer", nullable: false)]
    private int $durationDays;

    #[Column(type: "integer", nullable: false)]
    private int $lowSalary;

    #[Column(type: "integer", nullable: false)]
    private int $highSalary;

    #[Column(type: "integer", nullable: false)]
    private int $placeCount;

    #[Column(type: "string", nullable: false)]
    private string $title;

    #[Column(type: "string", nullable: false)]
    private string $description;

    #[Column(type: "boolean", nullable: false)]
    private bool $deleted;

    #[ManyToOne(targetEntity: Location::class)]
    private Location $location;

    #[JoinTable]
    #[JoinColumn(referencedColumnName: "id")]
    #[InverseJoinColumn(referencedColumnName: "id")]
    #[ManyToMany(targetEntity: Skill::class)]
    private Collection $skills;

    #[JoinTable]
    #[JoinColumn(referencedColumnName: "id")]
    #[InverseJoinColumn(referencedColumnName: "id", unique: true)]
    #[ManyToMany(targetEntity: Rate::class)]
    private Collection $rates;

    public function __construct()
    {
        $this->deleted = false;
        $this->skills = new ArrayCollection();
        $this->rates = new ArrayCollection();
    }

    /*-------------------------------------------------- Getters --------------------------------------------------*/

    public function getId(): int
    {
        return $this->id;
    }

    public function getStartDate(): DateTime
    {
        return $this->startDate;
    }

    public function getEndDate(): DateTime
    {
        return $this->endDate;
    }

    public function getDurationDays(): int
    {
        return $this->durationDays;
    }

    public function getLowSalary(): int
    {
        return $this->lowSalary;
    }

    public function getHighSalary(): int
    {
        return $this->highSalary;
    }

    public function getPlaceCount(): int
    {
        return $this->placeCount;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getDeleted(): bool
    {
        return $this->deleted;
    }

    public function getLocation(): Location
    {
        return $this->location;
    }

    public function getSkills(): Collection
    {
        return $this->skills;
    }

    public function getRates(): Collection
    {
        return $this->rates;
    }

    /*-------------------------------------------------- Setters --------------------------------------------------*/

    public function setStartDate(DateTime $startDate): self
    {
        $this->startDate = $startDate;
        return $this;
    }

    public function setEndDate(DateTime $endDate): self
    {
        $this->endDate = $endDate;
        return $this;
    }

    public function setDurationDays(int $durationDays): self
    {
        $this->durationDays = $durationDays;
        return $this;
    }

    public function setLowSalary(int $lowSalary): self
    {
        $this->lowSalary = $lowSalary;
        return $this;
    }

    public function setHighSalary(int $highSalary): self
    {
        $this->highSalary = $highSalary;
        return $this;
    }

    public function setPlaceCount(int $placeCount): self
    {
        $this->placeCount = $placeCount;
        return $this;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;
        return $this;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;
        return $this;
    }

    public function setDeleted(bool $deleted): self
    {
        $this->deleted = $deleted;
        return $this;
    }

    public function setLocation(Location $location): self
    {
        $this->location = $location;
        return $this;
    }

    public function setSkills(Collection $skills): self
    {
        $this->skills = $skills;
        return $this;
    }

    public function addSkill(Skill $skill): self
    {
        $this->skills->add($skill);
        return $this;
    }

    public function setRates(Collection $rates): self
    {
        $this->rates = $rates;
        return $this;
    }

    public function addRate(Rate $rate): self
    {
        $this->rates->add($rate);
        return $this;
    }
}