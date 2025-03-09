<?php

namespace App\Domain\Team\Event;

use App\Shared\ValueObjects\Uuid;

readonly class TeamRelocatedEvent
{
    public function __construct(
        private Uuid $teamId,
        private string $teamName,
        private string $oldCity,
        private string $newCity,
    ) {
    }

    public function getTeamId(): Uuid
    {
        return $this->teamId;
    }

    public function getOldCity(): string
    {
        return $this->oldCity;
    }

    public function getNewCity(): string
    {
        return $this->newCity;
    }

    public function getTeamName(): string
    {
        return $this->teamName;
    }
}
