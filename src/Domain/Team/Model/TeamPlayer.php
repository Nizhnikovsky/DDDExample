<?php


namespace App\Domain\Team\Model;

use App\Shared\ValueObjects\Uuid;
readonly class TeamPlayer
{
    public function __construct(
        private Uuid   $playerId,
        private string $firstName,
        private string $lastName,
    ){}

    /**
     * @return Uuid
     */
    public function getPlayerId(): Uuid
    {
        return $this->playerId;
    }

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }
}