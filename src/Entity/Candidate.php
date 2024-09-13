<?php

namespace App\Entity;

use App\Repository\CandidateRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CandidateRepository::class)]
class Candidate
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $lastname = null;

    #[ORM\Column(length: 255)]
    private ?string $phoneNumber = null;

    #[ORM\ManyToOne(inversedBy: 'candidates')]
    private ?User $user = null;

    /**
     * @var Collection<int, JobOffer>
     */
    #[ORM\ManyToMany(targetEntity: JobOffer::class, inversedBy: 'candidates')]
    private Collection $offers;

    public function __construct()
    {
        $this->offers = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->id . ' ' . $this->name . ' ' . $this->lastname;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): static
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(string $phoneNumber): static
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, JobOffer>
     */
    public function getOffers(): Collection
    {
        return $this->offers;
    }

    public function addOffer(JobOffer $offer): static
    {
        if (!$this->offers->contains($offer)) {
            $this->offers->add($offer);
        }

        return $this;
    }

    public function removeOffer(JobOffer $offer): static
    {
        $this->offers->removeElement($offer);

        return $this;
    }
}
