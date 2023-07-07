<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\CandidatRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: CandidatRepository::class)]
#[ApiResource]
#[UniqueEntity(
    fields: ['member', 'session'],
    message: 'This member is already candidat',
)]
class Candidat
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["candidat.list", "vote.list"])]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, options: ['default' => 'CURRENT_TIMESTAMP'])]
    #[Groups(["candidat.list"])]
    #[Assert\Type('datetime')]
    private ?\DateTimeInterface $date_created = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Assert\Type('datetime')]
    private ?\DateTimeInterface $dateUpdated = null;

    #[ORM\Column(options: ['default' => 0])]
    #[Groups(["candidat.list"])]
    private ?int $numberVoter = null;

    #[ORM\ManyToOne(inversedBy: 'candidats')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(["candidat.list", "vote.list"])]
    private ?Member $member = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(["candidat.list", "vote.list"])]
    private ?Position $position = null;

    #[ORM\ManyToOne(inversedBy: 'candidats')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(["candidat.list"])]
    private ?VotingSession $session = null;

    #[ORM\Column]
    #[Groups(["candidat.list"])]
    private ?bool $status = null;

    #[ORM\OneToMany(mappedBy: 'candidat', targetEntity: Vote::class, orphanRemoval: true)]
    private Collection $votes;

    public function __construct()
    {
        $this->votes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateCreated(): ?\DateTimeInterface
    {
        return $this->date_created;
    }

    public function setDateCreated(\DateTimeInterface $date_created): self
    {
        $this->date_created = $date_created;

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

    public function getNumberVoter(): ?int
    {
        return $this->numberVoter;
    }

    public function setNumberVoter(int $numberVoter): self
    {
        $this->numberVoter = $numberVoter;

        return $this;
    }

    public function IncNumberVoter(): self
    {
        $this->numberVoter += 1;
        return $this;
    }

    public function getMember(): ?Member
    {
        return $this->member;
    }

    public function setMember(?Member $member): self
    {
        $this->member = $member;

        return $this;
    }

    public function getPosition(): ?Position
    {
        return $this->position;
    }

    public function setPosition(?Position $position): self
    {
        $this->position = $position;

        return $this;
    }

    public function getSession(): ?VotingSession
    {
        return $this->session;
    }

    public function setSession(?VotingSession $session): self
    {
        $this->session = $session;

        return $this;
    }

    public function isStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function __toString()
    {
        return $this->session . ' ' . $this->member . ' ' . $this->position;
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
            $vote->setCandidat($this);
        }

        return $this;
    }

    public function removeVote(Vote $vote): self
    {
        if ($this->votes->removeElement($vote)) {
            // set the owning side to null (unless already changed)
            if ($vote->getCandidat() === $this) {
                $vote->setCandidat(null);
            }
        }

        return $this;
    }
}
