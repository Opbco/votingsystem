<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\ExaminationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ExaminationRepository::class)]
#[ApiResource]
class Examination
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["candidatConcour"])]
    private ?int $id = null;

    #[ORM\Column(length: 20)]
    #[Groups(["candidatConcour"])]
    private ?string $code = null;

    #[ORM\Column(length: 8)]
    #[Groups(["candidatConcour"])]
    private ?string $schoolYear = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(["candidatConcour"])]
    private ?\DateTimeInterface $openDate = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(["candidatConcour"])]
    private ?\DateTimeInterface $closingDate = null;

    #[ORM\OneToMany(mappedBy: 'concours', targetEntity: CandidatConcours::class)]
    private Collection $candidats;

    public function __construct()
    {
        $this->candidats = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getSchoolYear(): ?string
    {
        return $this->schoolYear;
    }

    public function setSchoolYear(string $schoolYear): self
    {
        $this->schoolYear = $schoolYear;

        return $this;
    }

    public function getOpenDate(): ?\DateTimeInterface
    {
        return $this->openDate;
    }

    public function setOpenDate(\DateTimeInterface $openDate): self
    {
        $this->openDate = $openDate;

        return $this;
    }

    public function getClosingDate(): ?\DateTimeInterface
    {
        return $this->closingDate;
    }

    public function setClosingDate(\DateTimeInterface $closingDate): self
    {
        $this->closingDate = $closingDate;

        return $this;
    }

    /**
     * @return Collection<int, CandidatConcours>
     */
    public function getCandidats(): Collection
    {
        return $this->candidats;
    }

    public function __toString()
    {
        return $this->code . ' ' . $this->schoolYear;
    }

    public function addCandidat(CandidatConcours $candidat): self
    {
        if (!$this->candidats->contains($candidat)) {
            $this->candidats->add($candidat);
            $candidat->setConcours($this);
        }

        return $this;
    }

    public function removeCandidat(CandidatConcours $candidat): self
    {
        if ($this->candidats->removeElement($candidat)) {
            // set the owning side to null (unless already changed)
            if ($candidat->getConcours() === $this) {
                $candidat->setConcours(null);
            }
        }

        return $this;
    }
}
