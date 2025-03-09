<?php


namespace App\Presentation\DTO\Response\Player;

readonly class PlayerTeamResponseDTO
{
    public function __construct(
        public string $id,
        public string $name
    ){
    }
}