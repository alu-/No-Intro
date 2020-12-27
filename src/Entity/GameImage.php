<?php

namespace App\Entity;

use App\Repository\GameImageRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=GameImageRepository::class)
 */
class GameImage
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Game::class, inversedBy="gameImages")
     * @ORM\JoinColumn(nullable=false)
     */
    private $game;

    /**
     * @ORM\Column(type="string", columnDefinition="enum('icon_url', 'medium_url', 'original_url', 'screen_url', 'screen_large_url', 'small_url', 'super_url', 'thumb_url', 'tiny_url')")
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=512)
     */
    private $url;

    /**
     * @ORM\Column(type="boolean")
     */
    private $is_downloaded;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $filename;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGame(): ?Game
    {
        return $this->game;
    }

    public function setGame(?Game $game): self
    {
        $this->game = $game;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getIsDownloaded(): ?bool
    {
        return $this->is_downloaded;
    }

    public function setIsDownloaded(bool $is_downloaded): self
    {
        $this->is_downloaded = $is_downloaded;

        return $this;
    }

    public function getFilename(): ?string
    {
        return $this->filename;
    }

    public function setFilename(?string $filename): self
    {
        $this->filename = $filename;

        return $this;
    }
}
