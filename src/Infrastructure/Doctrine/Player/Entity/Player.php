<?php


namespace App\src\Infrastructure\Doctrine\Player\Entity;

use App\Domain\Player\Enum\PlayerPositionEnum;
use App\src\Infrastructure\Doctrine\Player\Repository\PlayerRepository;
use App\src\Infrastructure\Doctrine\Team\Entity\Team;
use DateTimeInterface;
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
     * @var DateTimeInterface
     *
     * @ORM\Column(type="datetime")
     */
    private $joinedAt;

    #[ORM\ManyToOne(targetEntity: Team::class, inversedBy: 'players')]
    #[ORM\JoinColumn(name: 'team_id', referencedColumnName: 'id', nullable: false)]
    protected Team $team;

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param Uuid $id
     */
    public function setId(Uuid $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     */
    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     */
    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
    }

    /**
     * @return string
     */
    public function getAge(): string
    {
        return $this->age;
    }

    /**
     * @param string $age
     */
    public function setAge(string $age): void
    {
        $this->age = $age;
    }

    /**
     * @return string
     */
    public function getPlayerNumber(): string
    {
        return $this->playerNumber;
    }

    /**
     * @param string $playerNumber
     */
    public function setPlayerNumber(string $playerNumber): void
    {
        $this->playerNumber = $playerNumber;
    }

    /**
     * @return PlayerPositionEnum
     */
    public function getPosition(): PlayerPositionEnum
    {
        return $this->position;
    }

    /**
     * @param PlayerPositionEnum $position
     */
    public function setPosition(PlayerPositionEnum $position): void
    {
        $this->position = $position;
    }

    /**
     * @return DateTimeInterface
     */
    public function getJoinedAt(): DateTimeInterface
    {
        return $this->joinedAt;
    }

    /**
     * @param DateTimeInterface $joinedAt
     */
    public function setJoinedAt(DateTimeInterface $joinedAt): void
    {
        $this->joinedAt = $joinedAt;
    }

    /**
     * @return Team
     */
    public function getTeam(): Team
    {
        return $this->team;
    }

    /**
     * @param Team $team
     */
    public function setTeam(Team $team): void
    {
        $this->team = $team;
    }
}