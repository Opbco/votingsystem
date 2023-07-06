<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\ExamCenterRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ExamCenterRepository::class)]
#[ApiResource]
class ExamCenter
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["list.examcenter", "candidatConcour"])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(["list.examcenter", "candidatConcour"])]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Groups(["list.examcenter", "candidatConcour"])]
    private ?string $town = null;

    #[ORM\ManyToOne(inversedBy: 'examCenters')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(["list.examcenter", "candidatConcour"])]
    private ?Departement $division = null;

    #[ORM\OneToMany(mappedBy: 'ExamCenter', targetEntity: CandidatConcours::class)]
    private Collection $candidatConcours;

    public function __construct()
    {
        $this->candidatConcours = new ArrayCollection();
    }

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

    public function getTown(): ?string
    {
        return $this->town;
    }

    public function setTown(string $town): self
    {
        $this->town = $town;

        return $this;
    }

    public function getDivision(): ?Departement
    {
        return $this->division;
    }

    public function setDivision(?Departement $division): self
    {
        $this->division = $division;

        return $this;
    }

    /**
     * @return Collection<int, CandidatConcours>
     */
    public function getCandidatConcours(): Collection
    {
        return $this->candidatConcours;
    }

    public function addCandidatConcour(CandidatConcours $candidatConcour): self
    {
        if (!$this->candidatConcours->contains($candidatConcour)) {
            $this->candidatConcours->add($candidatConcour);
            $candidatConcour->setExamCenter($this);
        }

        return $this;
    }

    public function removeCandidatConcour(CandidatConcours $candidatConcour): self
    {
        if ($this->candidatConcours->removeElement($candidatConcour)) {
            // set the owning side to null (unless already changed)
            if ($candidatConcour->getExamCenter() === $this) {
                $candidatConcour->setExamCenter(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->name . ' ' . $this->getDivision();
    }
}
