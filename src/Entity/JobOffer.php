<?php

namespace App\Entity;

use App\Repository\JobOfferRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: JobOfferRepository::class)]
class JobOffer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateAdded = null;

    /**
     * @var Collection<int, Candidate>
     */
    #[ORM\ManyToMany(targetEntity: Candidate::class, mappedBy: 'offers')]
    private Collection $candidates;

    public function __construct()
    {
        $this->candidates = new ArrayCollection();
        $this->dateAdded = new DateTime();
    }

    public function __toString(): string
    {
        return $this->title;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getDateAdded(): ?\DateTimeInterface
    {
        return $this->dateAdded;
    }

    public function setDateAdded(\DateTimeInterface $dateAdded): static
    {
        $this->dateAdded = $dateAdded;

        return $this;
    }

/**
 * @return Collection<int, Candidate>
 */
public function getCandidates(): Collection
{
    return $this->candidates;
}

public function addCandidate(Candidate $candidate): static
{
    if (!$this->candidates->contains($candidate)) {
        $this->candidates->add($candidate);
        $candidate->addOffer($this);
    }

    return $this;
}

public function removeCandidate(Candidate $candidate): static
{
    if ($this->candidates->removeElement($candidate)) {
        $candidate->removeOffer($this);
    }

    return $this;
}
}
