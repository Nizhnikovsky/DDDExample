<?php


namespace App\Presentation\DTO\Response\Team;

readonly class TeamResponseDTO
{
    public function __construct(
        public string $teamId,
        public string $name,
        public int    $yearFounded,
        public string $stadium,
        public string $city,
        /** @var TeamPlayerResponseDTO[] */
        public array  $players
    ){}
}