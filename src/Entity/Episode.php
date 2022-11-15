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

    #[ORM\OneToMany(mappedBy: 'episode', targetEntity: Download::class, orphanRemoval: true)]
    private Collection $downloads;

    public function __construct()
    {
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
