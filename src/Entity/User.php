<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[Assert\Unique]
    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\ManyToMany(targetEntity: Episode::class, inversedBy: 'hosts')]
    private Collection $episodes;

    #[ORM\OneToMany(mappedBy: 'creator', targetEntity: Podcast::class)]
    private Collection $createdPodcasts;

    public function __construct()
    {
        $this->episodes = new ArrayCollection();
        $this->createdPodcasts = new ArrayCollection();
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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return Collection<int, Episode>
     */
    public function getEpisodes(): Collection
    {
        return $this->episodes;
    }

    public function addEpisode(Episode $episode): self
    {
        if (!$this->episodes->contains($episode)) {
            $this->episodes->add($episode);
        }

        return $this;
    }

    public function removeEpisode(Episode $episode): self
    {
        $this->episodes->removeElement($episode);

        return $this;
    }

    /**
     * @return Collection<int, Podcast>
     */
    public function getCreatedPodcasts(): Collection
    {
        return $this->createdPodcasts;
    }

    public function addCreatedPodcast(Podcast $createdPodcast): self
    {
        if (!$this->createdPodcasts->contains($createdPodcast)) {
            $this->createdPodcasts->add($createdPodcast);
            $createdPodcast->setCreator($this);
        }

        return $this;
    }

    public function removeCreatedPodcast(Podcast $createdPodcast): self
    {
        if ($this->createdPodcasts->removeElement($createdPodcast)) {
            // set the owning side to null (unless already changed)
            if ($createdPodcast->getCreator() === $this) {
                $createdPodcast->setCreator(null);
            }
        }

        return $this;
    }
}
