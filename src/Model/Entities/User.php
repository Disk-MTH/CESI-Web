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
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Table;
use stagify\Model\Repositories\UserRepo;

#[Entity/*(repositoryClass: UserRepo::class)*/, Table(name: "user")]
class User
{
    #[Id, Column(type: "integer"), GeneratedValue(strategy: "AUTO")]
    private int $id;

    #[Column(type: "integer", nullable: false)]
    private int $role;

    #[Column(type: "string", nullable: false)]
    private string $firstName;

    #[Column(type: "string", nullable: false)]
    private string $lastName;

    #[Column(type: "string", nullable: false)]
    private string $profilePicturePath;

    #[Column(type: "string", nullable: false)]
    private string $description;

    #[Column(type: "string", nullable: false)]
    private string $login;

    #[Column(type: "string", nullable: false)]
    private string $passwordHash;

    #[Column(type: "boolean", nullable: false, options: ["default" => false])]
    private bool $deleted;

    #[ManyToOne(targetEntity: Location::class)]
    private Location $location;

    #[JoinTable]
    #[JoinColumn(referencedColumnName: "id")]
    #[InverseJoinColumn(referencedColumnName: "id")]
    #[ManyToMany(targetEntity: Promo::class)]
    private Collection $promos;

    #[JoinTable]
    #[JoinColumn(referencedColumnName: "id")]
    #[InverseJoinColumn(referencedColumnName: "id")]
    #[ManyToMany(targetEntity: Skill::class)]
    private Collection $skills;

    #[JoinTable]
    #[JoinColumn(referencedColumnName: "id")]
    #[InverseJoinColumn(referencedColumnName: "id")]
    #[ManyToMany(targetEntity: Rate::class)]
    private Collection $rates;

    #[JoinTable]
    #[JoinColumn(referencedColumnName: "id")]
    #[InverseJoinColumn(referencedColumnName: "id")]
    #[ManyToMany(targetEntity: InternshipOffer::class)]
    private Collection $wishes;

    public function __construct()
    {
        $this->deleted = false;
        $this->promos = new ArrayCollection();
        $this->skills = new ArrayCollection();
        $this->rates = new ArrayCollection();
        $this->wishes = new ArrayCollection();
    }

    /*-------------------------------------------------- Getters --------------------------------------------------*/

    public function getId(): int
    {
        return $this->id;
    }

    public function getRole(): int
    {
        return $this->role;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getProfilePicturePath(): string
    {
        return $this->profilePicturePath;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getLogin(): string
    {
        return $this->login;
    }

    public function getPasswordHash(): string
    {
        return $this->passwordHash;
    }

    public function getDeleted(): bool
    {
        return $this->deleted;
    }

    public function getLocation(): Location
    {
        return $this->location;
    }

    public function getPromos(): Collection
    {
        return $this->promos;
    }

    public function getSkills(): Collection
    {
        return $this->skills;
    }

    public function getRates(): Collection
    {
        return $this->rates;
    }

    public function getWishes(): Collection
    {
        return $this->wishes;
    }

    /*-------------------------------------------------- Setters --------------------------------------------------*/

    public function setRole(int $role): self
    {
        $this->role = $role;
        return $this;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;
        return $this;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;
        return $this;
    }

    public function setProfilePicturePath(string $profilePicturePath): self
    {
        $this->profilePicturePath = $profilePicturePath;
        return $this;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;
        return $this;
    }

    public function setLogin(string $login): self
    {
        $this->login = $login;
        return $this;
    }

    public function setPasswordHash(string $passwordHash): self
    {
        $this->passwordHash = $passwordHash;
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

    public function addPromo(Promo $promo): self
    {
        $this->promos->add($promo);
        return $this;
    }

    public function removePromo(Promo $promo): self
    {
        $this->promos->removeElement($promo);
        return $this;
    }

    public function addSkill(Skill $skill): self
    {
        $this->skills->add($skill);
        return $this;
    }

    public function removeSkill(Skill $skill): self
    {
        $this->skills->removeElement($skill);
        return $this;
    }

    public function addRate(Rate $rate): self
    {
        $this->rates->add($rate);
        return $this;
    }

    public function removeRate(Rate $rate): self
    {
        $this->rates->removeElement($rate);
        return $this;
    }

    public function addWish(InternshipOffer $wish): self
    {
        $this->wishes->add($wish);
        return $this;
    }

    public function removeWish(InternshipOffer $wish): self
    {
        $this->wishes->removeElement($wish);
        return $this;
    }
}