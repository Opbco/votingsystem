<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\MemberRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: MemberRepository::class)]
#[ApiResource]
class Member
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["membre.list", "candidat.list", "vote.list"])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(["membre.list", "candidat.list", "vote.list"])]
    private ?string $fullName = null;

    #[ORM\Column(length: 12)]
    #[Groups(["membre.list"])]
    private ?string $code_elect = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[Groups(["membre.list", "candidat.list", "vote.list"])]
    private ?Document $avatar = null;

    #[ORM\OneToOne(inversedBy: 'member', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(["membre.list"])]
    private ?User $account = null;

    #[ORM\ManyToOne(inversedBy: 'members')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(["membre.list", "candidat.list", "vote.list"])]
    private ?Batch $batch = null;

    #[ORM\OneToMany(mappedBy: 'member', targetEntity: Candidat::class, orphanRemoval: true)]
    private Collection $candidats;

    #[ORM\OneToMany(mappedBy: 'member', targetEntity: Vote::class, orphanRemoval: true)]
    private Collection $votes;

    public function __construct()
    {
        $this->candidats = new ArrayCollection();
        $this->votes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFullName(): ?string
    {
        return $this->fullName;
    }

    public function setFullName(string $fullName): self
    {
        $this->fullName = $fullName;

        return $this;
    }

    public function getCodeElect(): ?string
    {
        return $this->code_elect;
    }

    public function setCodeElect(string $code_elect): self
    {
        $this->code_elect = $code_elect;

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

    public function getAccount(): ?User
    {
        return $this->account;
    }

    public function setAccount(User $account): self
    {
        $this->account = $account;

        return $this;
    }

    public function genCodeElect(): string
    {
        return $this->getBatch() . '-' . substr(str_replace(' ', '', $this->fullName), 0, 7);
    }

    public function __toString()
    {
        return $this->fullName;
    }

    public function getBatch(): ?Batch
    {
        return $this->batch;
    }

    public function setBatch(?Batch $batch): self
    {
        $this->batch = $batch;

        return $this;
    }

    /**
     * @return Collection<int, Candidat>
     */
    public function getCandidats(): Collection
    {
        return $this->candidats;
    }

    public function addCandidat(Candidat $candidat): self
    {
        if (!$this->candidats->contains($candidat)) {
            $this->candidats->add($candidat);
            $candidat->setMember($this);
        }

        return $this;
    }

    public function removeCandidat(Candidat $candidat): self
    {
        if ($this->candidats->removeElement($candidat)) {
            // set the owning side to null (unless already changed)
            if ($candidat->getMember() === $this) {
                $candidat->setMember(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Vote>
     */
    public function getVotes(): Collection
    {
        return $this->votes;
    }

    public function addVote(Vote $vote): self
    {
        if (!$this->votes->contains($vote)) {
            $this->votes->add($vote);
            $vote->setMember($this);
        }

        return $this;
    }

    public function removeVote(Vote $vote): self
    {
        if ($this->votes->removeElement($vote)) {
            // set the owning side to null (unless already changed)
            if ($vote->getMember() === $this) {
                $vote->setMember(null);
            }
        }

        return $this;
    }
}
