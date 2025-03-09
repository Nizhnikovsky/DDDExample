<?php

namespace App\Domain\Player\DTO;

class PlayerDTO
{
    public function __construct(
        public string $firstName,
        public string $lastName,
        public int $age,
        public int $playerNumber,
        public string $position,
    ) {
    }
}
