<?php

namespace App\Domain\Player\Model;

use App\Domain\Player\ValueObject\AgeValue;
use App\Domain\Player\ValueObject\NumberValue;
use App\Domain\Player\ValueObject\PositionValue;
use App\Shared\ValueObjects\Uuid;

class Player
{
    public function __construct(
        private readonly Uuid $playerId,
        private readonly string $firstName,
        private readonly string $lastName,
        private AgeValue $age,
        private NumberValue $playerNumber,
        private PositionValue $position,
        private PlayerTeam $team,
        private readonly \DateTimeImmutable $joinedAt,
    ) {
    }

    public function changePlayerAge(AgeValue $age): void
    {
        $this->age = $age;
    }

    public function changePlayerPosition(PositionValue $position): void
    {
        $this->position = $position;
    }

    public function changePlayerNumber(NumberValue $playerNumber): void
    {
        $this->playerNumber = $playerNumber;
    }

    public function changeTeam(PlayerTeam $team): void
    {
        $this->team = $team;
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

    public function getAge(): AgeValue
    {
        return $this->age;
    }

    public function getPlayerNumber(): NumberValue
    {
        return $this->playerNumber;
    }

    public function getPosition(): PositionValue
    {
        return $this->position;
    }

    public function getJoinedAt(): \DateTimeImmutable
    {
        return $this->joinedAt;
    }

    public function getTeam(): PlayerTeam
    {
        return $this->team;
    }
}
