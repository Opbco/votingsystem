<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\BatchRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BatchRepository::class)]
#[ApiResource]
class Batch
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["candidat.list", "membre.list", "vote.list"])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(["candidat.list", "membre.list"])]
    private ?string $name = null;

    #[ORM\Column(length: 20, unique: true)]
    #[Groups(["candidat.list", "membre.list", "vote.list"])]
    private ?string $code = null;

    #[ORM\OneToMany(mappedBy: 'batch', targetEntity: Member::class, orphanRemoval: true)]
    private Collection $members;

    public function __construct()
    {
        $this->members = new ArrayCollection();
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

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @return Collection<int, Member>
     */
    public function getMembers(): Collection
    {
        return $this->members;
    }

    public function addMember(Member $member): self
    {
        if (!$this->members->contains($member)) {
            $this->members->add($member);
            $member->setBatch($this);
        }

        return $this;
    }

    public function removeMember(Member $member): self
    {
        if ($this->members->removeElement($member)) {
            // set the owning side to null (unless already changed)
            if ($member->getBatch() === $this) {
                $member->setBatch(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->code;
    }
}
