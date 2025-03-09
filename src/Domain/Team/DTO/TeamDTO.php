<?php


namespace App\Domain\Team\DTO;

class TeamDTO
{
    public function __construct(
        public readonly string $name,
        public readonly int $yearFounded,
        public string $stadium,
        public string $city,
    ){}
}