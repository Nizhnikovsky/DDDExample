<?php

namespace App\Infrastructure\Doctrine\Team\Entity;

use App\Infrastructure\Doctrine\Player\Entity\Player;
use App\Infrastructure\Doctrine\Team\Repository\TeamRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: TeamRepository::class)]
#[ORM\Table(name: 'teams')]
class Team
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    private string $id;

    #[ORM\Column(type: 'string', length: 100, unique: true)]
    private string $name;

    #[ORM\Column(type: 'string', length: 100)]
    private string $city;

    #[ORM\Column(type: 'integer')]
    private int $yearFounded;

    #[ORM\Column(type: 'string', length: 100)]
    private string $stadium;

    #[ORM\OneToMany(targetEntity: Player::class, mappedBy: 'team', cascade: ['persist', 'remove'])]
    private Collection $players;

    public function __construct()
    {
        $this->players = new ArrayCollection();
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(Uuid $id): void
    {
        $this->id = $id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function setCity(string $city): void
    {
        $this->city = $city;
    }

    public function getYearFounded(): int
    {
        return $this->yearFounded;
    }

    public function setYearFounded(int $yearFounded): void
    {
        $this->yearFounded = $yearFounded;
    }

    public function getStadium(): string
    {
        return $this->stadium;
    }

    public function setStadium(string $stadium): void
    {
        $this->stadium = $stadium;
    }

    public function getPlayers(): Collection
    {
        return $this->players;
    }

    public function setPlayers(array $players): self
    {
        $this->players->clear();
        foreach ($players as $player) {
            $this->players->add($player);
        }

        return $this;
    }

    public function setPlayer(Player $player): self
    {
        if (false === $this->players->contains($player)) {
            $player->setTeam($this);
            $this->players->add($player);
        }

        return $this;
    }
}
