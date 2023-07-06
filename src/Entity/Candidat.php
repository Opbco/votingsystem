<?php

namespace App\Entity;

use App\Repository\CandidatRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinTable;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: CandidatRepository::class)]
class Candidat
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["candidate:list", "candidatConcour"])]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["candidate:list", "candidatConcour"])]
    private ?string $firstName = null;

    #[ORM\Column(length: 255)]
    #[Groups(["candidate:list", "candidatConcour"])]
    private ?string $lastName = null;

    #[ORM\Column(length: 20)]
    #[Groups(["candidate:list", "candidatConcour"])]
    private ?string $gender = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Groups(["candidate:list", "candidatConcour"])]
    private ?\DateTimeInterface $dob = null;

    #[ORM\Column(length: 255)]
    #[Groups(["candidate:list", "candidatConcour"])]
    private ?string $pob = null;

    #[ORM\Column(length: 255)]
    #[Groups(["candidate:list", "candidatConcour"])]
    private ?string $nationality = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(["candidate:list", "candidatConcour"])]
    private ?Departement $divisionOrigin = null;

    #[ORM\Column(length: 255)]
    #[Groups(["candidate:list", "candidatConcour"])]
    private ?string $email = null;

    #[ORM\Column(length: 15, nullable: true)]
    #[Groups(["candidate:list", "candidatConcour"])]
    #[Assert\Regex(
        pattern: '/^237[0-9]{9}+$/i',
        htmlPattern: '^(237[0-9]{9})$'
    )]
    private ?string $phone = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["candidate:list", "candidatConcour"])]
    private ?string $fatherName = null;

    #[ORM\Column(length: 255)]
    #[Groups(["candidate:list", "candidatConcour"])]
    private ?string $motherName = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["candidate:list", "candidatConcour"])]
    private ?string $parentsPhones = null;

    #[ORM\Column(length: 255)]
    #[Groups(["candidate:list", "candidatConcour"])]
    private ?string $bp = null;

    #[ORM\OneToMany(mappedBy: 'candidat', targetEntity: CandidatConcours::class, orphanRemoval: true)]
    private Collection $concours;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: true)]
    #[Groups(["candidate:list", "candidatConcour"])]
    private ?Document $avatar = null;

    #[ORM\Column(length: 20, nullable: true)]
    #[Groups(["candidate:list", "candidatConcour"])]
    #[Assert\Regex(
        pattern: '/^237[0-9]{9}+$/i',
        htmlPattern: '^(237[0-9]{9})$'
    )]
    private ?string $whatsapp = null;

    #[ORM\OneToMany(mappedBy: 'candidat', targetEntity: Diploma::class, orphanRemoval: true, cascade: ['persist', 'remove'])]
    #[Groups(["candidatConcour"])]
    private Collection $diplomas;

    public function __construct()
    {
        $this->concours = new ArrayCollection();
        $this->diplomas = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->firstName . ' ' . $this->lastName;
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

    public function getNationality(): ?string
    {
        return $this->nationality;
    }

    public function setNationality(string $nationality): self
    {
        $this->nationality = $nationality;

        return $this;
    }

    public function getDivisionOrigin(): ?Departement
    {
        return $this->divisionOrigin;
    }

    public function setDivisionOrigin(?Departement $divisionOrigin): self
    {
        $this->divisionOrigin = $divisionOrigin;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

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

    public function getMotherName(): ?string
    {
        return $this->motherName;
    }

    public function setMotherName(string $motherName): self
    {
        $this->motherName = $motherName;

        return $this;
    }

    public function getParentsPhones(): ?string
    {
        return $this->parentsPhones;
    }

    public function setParentsPhones(?string $parentsPhones): self
    {
        $this->parentsPhones = $parentsPhones;

        return $this;
    }

    public function getBp(): ?string
    {
        return $this->bp;
    }

    public function setBp(string $bp): self
    {
        $this->bp = $bp;

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

    /**
     * @return Collection<int, CandidatConcours>
     */
    public function getConcours(): Collection
    {
        return $this->concours;
    }

    public function addConcour(CandidatConcours $concour): self
    {
        if (!$this->concours->contains($concour)) {
            $this->concours->add($concour);
            $concour->setCandidat($this);
        }

        return $this;
    }

    public function removeConcour(CandidatConcours $concour): self
    {
        if ($this->concours->removeElement($concour)) {
            // set the owning side to null (unless already changed)
            if ($concour->getCandidat() === $this) {
                $concour->setCandidat(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->firstName . ' ' . $this->lastName;
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

    /**
     * @return Collection<int, Diploma>
     */
    public function getDiplomas(): Collection
    {
        return $this->diplomas;
    }

    public function addDiploma(Diploma $diploma): self
    {
        if (!$this->diplomas->contains($diploma)) {
            $this->diplomas->add($diploma);
            $diploma->setCandidat($this);
        }

        return $this;
    }

    public function removeDiploma(Diploma $diploma): self
    {
        if ($this->diplomas->removeElement($diploma)) {
            // set the owning side to null (unless already changed)
            if ($diploma->getCandidat() === $this) {
                $diploma->setCandidat(null);
            }
        }

        return $this;
    }
}
