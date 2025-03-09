<?php

namespace App\Domain\Team\Model;

use App\Shared\ValueObjects\Uuid;

readonly class TeamPlayer
{
    public function __construct(
        private Uuid $playerId,
        private string $firstName,
        private string $lastName,
    ) {
    }

    public function getPlayerId(): Uuid
    {
        return $this->playerId;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }
}
