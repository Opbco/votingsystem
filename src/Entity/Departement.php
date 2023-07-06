<?php

namespace App\Entity;

use App\Repository\DepartementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: DepartementRepository::class)]
#[UniqueEntity(
    fields: ['state', 'name'],
    errorPath: 'name',
    message: 'This division is already in that state.',
)]
class Departement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["getMembres", "getMyStructures", "paroisses", "divisions", "list.examcenter", "candidatConcour"])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Groups(["getMembres", "getMyStructures", "paroisses", "divisions", "list.examcenter", "candidatConcour"])]
    private ?string $name = null;

    #[ORM\ManyToOne(inversedBy: 'departements')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(["getMembres", "getMyStructures", "paroisses", "divisions"])]
    private ?State $state = null;

    #[ORM\OneToMany(mappedBy: 'department', targetEntity: SubDivision::class, orphanRemoval: true)]
    private Collection $subDivisions;

    #[ORM\OneToMany(mappedBy: 'division', targetEntity: ExamCenter::class, orphanRemoval: true)]
    private Collection $examCenters;

    public function __construct()
    {
        $this->subDivisions = new ArrayCollection();
        $this->examCenters = new ArrayCollection();
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

    public function getState(): ?State
    {
        return $this->state;
    }

    public function setState(?State $state): self
    {
        $this->state = $state;

        return $this;
    }

    /**
     * @return Collection<int, SubDivision>
     */
    public function getSubDivisions(): Collection
    {
        return $this->subDivisions;
    }

    public function addSubDivision(SubDivision $subDivision): self
    {
        if (!$this->subDivisions->contains($subDivision)) {
            $this->subDivisions->add($subDivision);
            $subDivision->setDepartment($this);
        }

        return $this;
    }

    public function removeSubDivision(SubDivision $subDivision): self
    {
        if ($this->subDivisions->removeElement($subDivision)) {
            // set the owning side to null (unless already changed)
            if ($subDivision->getDepartment() === $this) {
                $subDivision->setDepartment(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->name;
    }

    /**
     * @return Collection<int, ExamCenter>
     */
    public function getExamCenters(): Collection
    {
        return $this->examCenters;
    }

    public function addExamCenter(ExamCenter $examCenter): self
    {
        if (!$this->examCenters->contains($examCenter)) {
            $this->examCenters->add($examCenter);
            $examCenter->setDivision($this);
        }

        return $this;
    }

    public function removeExamCenter(ExamCenter $examCenter): self
    {
        if ($this->examCenters->removeElement($examCenter)) {
            // set the owning side to null (unless already changed)
            if ($examCenter->getDivision() === $this) {
                $examCenter->setDivision(null);
            }
        }

        return $this;
    }
}
