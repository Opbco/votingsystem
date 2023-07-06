<?php

namespace App\Entity;

use App\Repository\SubDivisionRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: SubDivisionRepository::class)]
#[UniqueEntity(
    fields: ['department', 'name'],
    errorPath: 'name',
    message: 'This Subdivision is already in that Division.',
)]
class SubDivision
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["getMembres", "getMyStructures", "paroisses", "subdivisions", "structures"])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Groups(["getMembres", "getMyStructures", "paroisses", "subdivisions", "structures"])]
    private ?string $name = null;

    #[ORM\ManyToOne(inversedBy: 'subDivisions')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(["getMembres", "getMyStructures", "paroisses"])]
    private ?Departement $department = null;

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

    public function getDepartment(): ?Departement
    {
        return $this->department;
    }

    public function setDepartment(?Departement $department): self
    {
        $this->department = $department;

        return $this;
    }

    public function __toString()
    {
        return $this->name;
    }
}
