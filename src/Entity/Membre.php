<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\MembreRepository;
use Doctrine\DBAL\Types\Types;
use Oh\GoogleMapFormTypeBundle\Traits\LocationTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: MembreRepository::class)]
#[ApiResource(
    collectionOperations: ['get' => ['normalization_context' => ['groups' => 'getMembres']]],
    order: ['dob' => 'DESC', 'name' => 'ASC'],
    paginationEnabled: true,
)]
class Membre
{
    use LocationTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["getMembres"])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Groups(["getMembres"])]
    private ?string $name = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\Type('datetime')]
    #[Groups(["getMembres"])]
    private ?\DateTimeInterface $dob = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Groups(["getMembres"])]
    private ?string $pob = null;

    #[ORM\Column(length: 50, nullable: true)]
    #[Groups(["getMembres"])]
    private ?string $gender = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["getMembres"])]
    private ?string $maritalStatus = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["getMembres"])]
    private ?string $ethnie = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["getMembres"])]
    private ?string $profession = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["getMembres"])]
    private ?string $handicap = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["getMembres"])]
    private ?string $conjointName = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["getMembres"])]
    private ?string $conjointFonction = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["getMembres"])]
    private ?string $conjointAdress = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["getMembres"])]
    private ?string $conjointContact = null;

    #[ORM\Column(nullable: true)]
    #[Groups(["getMembres"])]
    private ?int $nbreEnfant = null;

    #[ORM\Column(length: 24, nullable: true)]
    #[Groups(["getMembres"])]
    private ?string $phone = null;

    #[ORM\Column(length: 24, nullable: true)]
    #[Groups(["getMembres"])]
    private ?string $whatsapp = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["getMembres"])]
    private ?string $motherName = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["getMembres"])]
    private ?string $fatherName = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[Groups(["getMembres"])]
    private ?Document $avatar = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $account = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["getMembres"])]
    private ?string $title = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["getMembres"])]
    private ?string $description = null;

    #[ORM\Column(options: ['default' => 0])]
    #[Groups(["getMembres"])]
    private ?bool $isFeatured = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDob(): ?\DateTimeInterface
    {
        return $this->dob;
    }

    public function setDob(\DateTimeInterface $dob): self
    {
        $this->dob = $dob;

        return $this;
    }

    public function getPob(): ?string
    {
        return $this->pob;
    }

    public function setPob(string $pob): self
    {
        $this->pob = $pob;

        return $this;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(string $gender): self
    {
        $this->gender = $gender;

        return $this;
    }

    public function getMaritalStatus(): ?string
    {
        return $this->maritalStatus;
    }

    public function setMaritalStatus(string $maritalStatus): self
    {
        $this->maritalStatus = $maritalStatus;

        return $this;
    }

    public function getEthnie(): ?string
    {
        return $this->ethnie;
    }

    public function setEthnie(string $ethnie): self
    {
        $this->ethnie = $ethnie;

        return $this;
    }

    public function getProfession(): ?string
    {
        return $this->profession;
    }

    public function setProfession(string $profession): self
    {
        $this->profession = $profession;

        return $this;
    }

    public function getHandicap(): ?string
    {
        return $this->handicap;
    }

    public function setHandicap(?string $handicap): self
    {
        $this->handicap = $handicap;

        return $this;
    }

    public function getConjointName(): ?string
    {
        return $this->conjointName;
    }

    public function setConjointName(?string $conjointName): self
    {
        $this->conjointName = $conjointName;

        return $this;
    }

    public function getConjointFonction(): ?string
    {
        return $this->conjointFonction;
    }

    public function setConjointFonction(?string $conjointFonction): self
    {
        $this->conjointFonction = $conjointFonction;

        return $this;
    }

    public function getConjointAdress(): ?string
    {
        return $this->conjointAdress;
    }

    public function setConjointAdress(?string $conjointAdress): self
    {
        $this->conjointAdress = $conjointAdress;

        return $this;
    }

    public function getConjointContact(): ?string
    {
        return $this->conjointContact;
    }

    public function setConjointContact(?string $conjointContact): self
    {
        $this->conjointContact = $conjointContact;

        return $this;
    }

    public function getNbreEnfant(): ?int
    {
        return $this->nbreEnfant;
    }

    public function setNbreEnfant(int $nbreEnfant): self
    {
        $this->nbreEnfant = $nbreEnfant;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getWhatsapp(): ?string
    {
        return $this->whatsapp;
    }

    public function setWhatsapp(?string $whatsapp): self
    {
        $this->whatsapp = $whatsapp;

        return $this;
    }

    public function getMotherName(): ?string
    {
        return $this->motherName;
    }

    public function setMotherName(?string $motherName): self
    {
        $this->motherName = $motherName;

        return $this;
    }

    public function getFatherName(): ?string
    {
        return $this->fatherName;
    }

    public function setFatherName(?string $fatherName): self
    {
        $this->fatherName = $fatherName;

        return $this;
    }

    public function getAvatar(): ?Document
    {
        return $this->avatar;
    }

    public function setAvatar(?Document $avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }

    public function __toString()
    {
        return $this->name;
    }

    public function getAccount(): ?User
    {
        return $this->account;
    }

    public function setAccount(User $account): self
    {
        $this->account = $account;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function isIsFeatured(): ?bool
    {
        return $this->isFeatured;
    }

    public function setIsFeatured(bool $isFeatured): self
    {
        $this->isFeatured = $isFeatured;

        return $this;
    }
}
