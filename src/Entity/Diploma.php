<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\DiplomaRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: DiplomaRepository::class)]
#[ApiResource]
class Diploma
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["candidatConcour"])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(["candidatConcour"])]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Groups(["candidatConcour"])]
    private ?string $speciality = null;

    #[ORM\Column(length: 8)]
    #[Groups(["candidatConcour"])]
    private ?string $year = null;

    #[ORM\Column]
    #[Groups(["candidatConcour"])]
    private ?float $score = null;

    #[ORM\Column(length: 255)]
    #[Groups(["candidatConcour"])]
    private ?string $school = null;

    #[ORM\ManyToOne(inversedBy: 'diplomas')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Candidat $candidat = null;

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

    public function getSpeciality(): ?string
    {
        return $this->speciality;
    }

    public function setSpeciality(string $speciality): self
    {
        $this->speciality = $speciality;

        return $this;
    }

    public function getYear(): ?string
    {
        return $this->year;
    }

    public function setYear(string $year): self
    {
        $this->year = $year;

        return $this;
    }

    public function getScore(): ?float
    {
        return $this->score;
    }

    public function setScore(float $score): self
    {
        $this->score = $score;

        return $this;
    }

    public function getSchool(): ?string
    {
        return $this->school;
    }

    public function setSchool(string $school): self
    {
        $this->school = $school;

        return $this;
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
}
