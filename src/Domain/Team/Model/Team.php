<?php


namespace App\Domain\Team\Model;

use App\Domain\Team\Event\TeamRelocatedEvent;
use App\Shared\Exception\TeamAmountExceededException;
use App\Shared\ValueObjects\Uuid;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class Team
{
    public function __construct(
        private readonly Uuid $teamId,
        private readonly string $name,
        private readonly int $yearFounded,
        private string $stadium,
        private string $city,
        private array $players = []
    ){}

    /**
     * @throws TeamAmountExceededException
     */
    public function addPlayer(TeamPlayer $player): void
    {
        if (count($this->players) >= 11) {
            throw new TeamAmountExceededException();
        }

        $this->players[] = $player;
    }

    public function relocate(string $newCity, EventDispatcherInterface $eventDispatcher): void
    {
        if ($this->city === $newCity) {
            return;
        }

        $oldCity = $this->city;
        $this->city = $newCity;

        $eventDispatcher->dispatch(new TeamRelocatedEvent($this->teamId, $this->name, $oldCity, $newCity));
    }

    public function changeStadium(string $stadium): void
    {
        $this->stadium = $stadium;
    }

    /**
     * @return Uuid
     */
    public function getTeamId(): Uuid
    {
        return $this->teamId;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function getYearFounded(): int
    {
        return $this->yearFounded;
    }

    /**
     * @return string
     */
    public function getStadium(): string
    {
        return $this->stadium;
    }

    /**
     * @return string
     */
    public function getCity(): string
    {
        return $this->city;
    }

    /**
     * @return array
     */
    public function getPlayers(): array
    {
        return $this->players;
    }

    public function isFull(): bool
    {
        return count($this->players) == 11;
    }
}