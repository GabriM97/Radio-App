<?php

namespace App\Entity;

use App\Repository\EpisodeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EpisodeRepository::class)]
class Episode
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    private ?string $topic = null;

    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'episodes')]
    private Collection $hosts;

    #[ORM\ManyToOne(inversedBy: 'episodes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Podcast $podcast = null;

    #[ORM\OneToMany(mappedBy: 'episode', targetEntity: Download::class, orphanRemoval: true)]
    private Collection $downloads;

    public function __construct()
    {
        $this->hosts = new ArrayCollection();
        $this->downloads = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getTopic(): ?string
    {
        return $this->topic;
    }

    public function setTopic(string $topic): self
    {
        $this->topic = $topic;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getHosts(): Collection
    {
        return $this->hosts;
    }

    public function addHost(User $host): self
    {
        if (!$this->hosts->contains($host)) {
            $this->hosts->add($host);
            $host->addEpisode($this);
        }

        return $this;
    }

    public function removeHost(User $host): self
    {
        if ($this->hosts->removeElement($host)) {
            $host->removeEpisode($this);
        }

        return $this;
    }

    public function getPodcast(): ?Podcast
    {
        return $this->podcast;
    }

    public function setPodcast(?Podcast $podcast): self
    {
        $this->podcast = $podcast;

        return $this;
    }

    /**
     * @return Collection<int, Download>
     */
    public function getDownloads(): Collection
    {
        return $this->downloads;
    }

    public function addDownload(Download $download): self
    {
        if (!$this->downloads->contains($download)) {
            $this->downloads->add($download);
            $download->setEpisode($this);
        }

        return $this;
    }

    public function removeDownload(Download $download): self
    {
        if ($this->downloads->removeElement($download)) {
            // set the owning side to null (unless already changed)
            if ($download->getEpisode() === $this) {
                $download->setEpisode(null);
            }
        }

        return $this;
    }
}
