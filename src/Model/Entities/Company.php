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
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;
use stagify\Model\Repositories\CompanyRepo;

#[Entity(repositoryClass: CompanyRepo::class), Table(name: "company")]
class Company
{
    #[Id, Column(type: "integer"), GeneratedValue(strategy: "AUTO")]
    private int $id;

    #[Column(type: "string", nullable: false)]
    private string $name;

    #[Column(type: "string", nullable: false)]
    private string $website;

    #[Column(type: "integer", nullable: false)]
    private int $employeeCount;

    #[Column(type: "string", nullable: false)]
    private string $logoPicture;

    #[Column(type: "boolean", nullable: false, options: ["default" => false])]
    private bool $deleted;

    #[ManyToOne(cascade: ["persist"], inversedBy: "activity_sector")]
    private ActivitySector $activitySector;

    #[JoinTable]
    #[JoinColumn(referencedColumnName: "id")]
    #[InverseJoinColumn(referencedColumnName: "id")]
    #[ManyToMany(targetEntity: Location::class, cascade: ["persist"])]
    private Collection $locations;

    #[JoinTable]
    #[JoinColumn(referencedColumnName: "id")]
    #[InverseJoinColumn(referencedColumnName: "id")]
    #[ManyToMany(targetEntity: Internship::class)]
    private Collection $internships;

    public function __construct()
    {
        $this->deleted = false;
        $this->locations = new ArrayCollection();
        $this->internships = new ArrayCollection();
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

    public function getWebsite(): string
    {
        return $this->website;
    }

    public function getEmployeeCount(): int
    {
        return $this->employeeCount;
    }

    public function getLogoPicture(): string
    {
        return $this->logoPicture;
    }

    public function getDeleted(): bool
    {
        return $this->deleted;
    }

    public function getActivitySector(): ActivitySector
    {
        return $this->activitySector;
    }

    public function getLocations(): Collection
    {
        return $this->locations;
    }

    public function getInternships(): Collection
    {
        return $this->internships;
    }

    /*-------------------------------------------------- Setters --------------------------------------------------*/

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function setWebsite(string $website): self
    {
        $this->website = $website;
        return $this;
    }

    public function setEmployeeCount(int $employeeCount): self
    {
        $this->employeeCount = $employeeCount;
        return $this;
    }

    public function setLogoPicture(string $logoPicture): self
    {
        $this->logoPicture = $logoPicture;
        return $this;
    }

    public function setDeleted(bool $deleted): self
    {
        $this->deleted = $deleted;
        return $this;
    }

    public function setActivitySector(ActivitySector $activitySector): self
    {
        $this->activitySector = $activitySector;
        return $this;
    }

    public function setLocations(array $locations): self
    {
        $this->locations = new ArrayCollection($locations);
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

    public function addInternship(Internship $internship): self
    {
        $this->internships->add($internship);
        return $this;
    }

    public function removeInternship(Internship $internship): self
    {
        $this->internships->removeElement($internship);
        return $this;
    }
}