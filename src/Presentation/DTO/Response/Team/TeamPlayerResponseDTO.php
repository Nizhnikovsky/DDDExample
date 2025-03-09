<?php


namespace App\Presentation\DTO\Response\Team;

readonly class TeamPlayerResponseDTO
{
    public function __construct(
        public string $playerId,
        public string $firstName,
        public string $lastName,
    ){
    }
}