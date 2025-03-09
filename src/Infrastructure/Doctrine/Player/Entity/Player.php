<?php

namespace App\Infrastructure\Doctrine\Player\Entity;

use App\Domain\Player\Enum\PlayerPositionEnum;
use App\Infrastructure\Doctrine\Player\Repository\PlayerRepository;
use App\Infrastructure\Doctrine\Team\Entity\Team;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: PlayerRepository::class)]
#[ORM\Table(name: 'players')]
class Player
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    private string $id;

    #[ORM\Column(type: 'string', length: 100, unique: true)]
    private string $firstName;

    #[ORM\Column(type: 'string', length: 100)]
    private string $lastName;

    #[ORM\Column(type: 'integer')]
    private string $age;

    #[ORM\Column(type: 'integer', unique: true)]
    private string $playerNumber;

    #[ORM\Column(enumType: PlayerPositionEnum::class)]
    private PlayerPositionEnum $position;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    /**
     * @var \DateTimeInterface
     *
     * @ORM\Column(type="datetime")
     */
    private $joinedAt;

    #[ORM\ManyToOne(targetEntity: Team::class, inversedBy: 'players')]
    #[ORM\JoinColumn(name: 'team_id', referencedColumnName: 'id', nullable: false)]
    protected Team $team;

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(Uuid $id): void
    {
        $this->id = $id;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
    }

    public function getAge(): string
    {
        return $this->age;
    }

    public function setAge(string $age): void
    {
        $this->age = $age;
    }

    public function getPlayerNumber(): string
    {
        return $this->playerNumber;
    }

    public function setPlayerNumber(string $playerNumber): void
    {
        $this->playerNumber = $playerNumber;
    }

    public function getPosition(): PlayerPositionEnum
    {
        return $this->position;
    }

    public function setPosition(PlayerPositionEnum $position): void
    {
        $this->position = $position;
    }

    public function getJoinedAt(): \DateTimeInterface
    {
        return $this->joinedAt;
    }

    public function setJoinedAt(\DateTimeInterface $joinedAt): void
    {
        $this->joinedAt = $joinedAt;
    }

    public function getTeam(): Team
    {
        return $this->team;
    }

    public function setTeam(Team $team): void
    {
        $this->team = $team;
    }
}
