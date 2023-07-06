<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\CandidatConcoursRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CandidatConcoursRepository::class)]
#[ApiResource]
class CandidatConcours
{
    const STATUS = ['registered', 'valid', 'rejected', 'admitted'];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["candidatConcour"])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'concours')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(["candidatConcour"])]
    private ?Candidat $candidat = null;

    #[ORM\ManyToOne(inversedBy: 'candidats')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(["candidatConcour"])]
    private ?Examination $concours = null;

    #[ORM\Column(length: 255)]
    #[Groups(["candidatConcour"])]
    private ?string $level = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, options: ['default' => 'CURRENT_TIMESTAMP'])]
    #[Groups(["candidatConcour"])]
    private ?\DateTimeInterface $dateCreated = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateUpdated = null;

    #[ORM\Column(length: 40)]
    #[Groups(["candidatConcour"])]
    private ?string $language = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(["candidatConcour"])]
    private ?Speciality $speciality = null;

    #[ORM\Column(type: Types::SMALLINT)]
    #[Groups(["candidatConcour"])]
    private ?int $nbAttempt = null;

    #[ORM\ManyToOne(inversedBy: 'candidatConcours')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(["candidatConcour"])]
    private ?ExamCenter $examCenter = null;

    #[ORM\Column(length: 25)]
    #[Assert\Choice(choices: self::STATUS, message: 'Choose a valid status.')]
    #[Groups(["candidatConcour"])]
    private ?string $status = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCandidat(): ?Candidat
    {
        return $this->candidat;
    }

    public function setCandidat(?Candidat $candidat): self
    {
        $this->candidat = $candidat;

        return $this;
    }

    public function getConcours(): ?Examination
    {
        return $this->concours;
    }

    public function setConcours(?Examination $concours): self
    {
        $this->concours = $concours;

        return $this;
    }

    public function getLevel(): ?string
    {
        return $this->level;
    }

    public function setLevel(string $level): self
    {
        $this->level = $level;

        return $this;
    }

    public function getDateCreated(): ?\DateTimeInterface
    {
        return $this->dateCreated;
    }

    public function setDateCreated(\DateTimeInterface $dateCreated): self
    {
        $this->dateCreated = $dateCreated;

        return $this;
    }

    public function getDateUpdated(): ?\DateTimeInterface
    {
        return $this->dateUpdated;
    }

    public function setDateUpdated(\DateTimeInterface $dateUpdated): self
    {
        $this->dateUpdated = $dateUpdated;

        return $this;
    }

    public function getLanguage(): ?string
    {
        return $this->language;
    }

    public function setLanguage(string $language): self
    {
        $this->language = $language;

        return $this;
    }

    public function getSpeciality(): ?Speciality
    {
        return $this->speciality;
    }

    public function setSpeciality(?Speciality $speciality): self
    {
        $this->speciality = $speciality;

        return $this;
    }

    public function getNbAttempt(): ?int
    {
        return $this->nbAttempt;
    }

    public function setNbAttempt(int $nbAttempt): self
    {
        $this->nbAttempt = $nbAttempt;

        return $this;
    }

    public function getExamCenter(): ?ExamCenter
    {
        return $this->examCenter;
    }

    public function setExamCenter(?ExamCenter $ExamCenter): self
    {
        $this->examCenter = $ExamCenter;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function __toString()
    {
        return $this->getCandidat() . ' ' . $this->getConcours() . ' ' . $this->getDateCreated()->format('Y-m');
    }
}
