<?php

namespace App\Presentation\DTO\Response\Player;

readonly class PlayerResponseDTO
{
    public function __construct(
        public string $playerId,
        public string $firstName,
        public string $lastName,
        public int $age,
        public int $playerNumber,
        public string $position,
        public string $joinedAt,
        public PlayerTeamResponseDTO $team,
    ) {
    }
}
