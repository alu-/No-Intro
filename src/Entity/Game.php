<?php

namespace App\Entity;

use App\Repository\GameRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=GameRepository::class)
 * @ORM\Table(indexes={@ORM\Index(columns={"name", "aliases"}, flags={"fulltext"})})
 * @UniqueEntity("giantbomb_guid")
 */
class Game
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"api"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     * @Groups({"api"})
     */
    private $name;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"api"})
     */
    private $aliases;

    /**
     * @ORM\Column(type="text")
     * @Groups({"api"})
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=16, unique=true)
     * @Assert\NotBlank
     */
    private $giantbomb_guid;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @Groups({"api"})
     */
    private $original_release_date;

    /**
     * @ORM\OneToMany(targetEntity=GameImage::class, mappedBy="game",
     * orphanRemoval=true, cascade={"persist"})
     */
    private $gameImages;

    public function __construct()
    {
        $this->gameImages = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->getId() . ': ' . $this->getName();
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

    public function getAliases(): ?string
    {
        return $this->aliases;
    }

    public function setAliases(?string $aliases): self
    {
        $this->aliases = $aliases;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getGiantbombGuid(): ?string
    {
        return $this->giantbomb_guid;
    }

    public function setGiantbombGuid(string $giantbomb_guid): self
    {
        $this->giantbomb_guid = $giantbomb_guid;

        return $this;
    }

    public function getOriginalReleaseDate(): ?\DateTimeInterface
    {
        return $this->original_release_date;
    }

    public function setOriginalReleaseDate(?\DateTimeInterface $original_release_date): self
    {
        $this->original_release_date = $original_release_date;

        return $this;
    }

    /**
     * @return Collection|GameImage[]
     */
    public function getGameImages(): Collection
    {
        return $this->gameImages;
    }

    public function addGameImage(GameImage $gameImage): self
    {
        if (!$this->gameImages->contains($gameImage)) {
            $this->gameImages[] = $gameImage;
            $gameImage->setGame($this);
        }

        return $this;
    }

    public function removeGameImage(GameImage $gameImage): self
    {
        if ($this->gameImages->removeElement($gameImage)) {
            // set the owning side to null (unless already changed)
            if ($gameImage->getGame() === $this) {
                $gameImage->setGame(null);
            }
        }

        return $this;
    }
}
