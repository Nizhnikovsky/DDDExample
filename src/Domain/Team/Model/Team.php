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
        private array $players = [],
    ) {
    }

    /**
     * @throws TeamAmountExceededException
     */
    public function addPlayer(TeamPlayer $player): void
    {
        if (count($this->players) == 11) {
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

    public function getTeamId(): Uuid
    {
        return $this->teamId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getYearFounded(): int
    {
        return $this->yearFounded;
    }

    public function getStadium(): string
    {
        return $this->stadium;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function getPlayers(): array
    {
        return $this->players;
    }

    public function isFull(): bool
    {
        return 11 == count($this->players);
    }
}
