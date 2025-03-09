<?php

namespace App\Domain\Player\DTO;

use App\Shared\ValueObjects\Uuid;

class UpdatePlayerDTO
{
    public function __construct(
        public Uuid $id,
        public int $age,
        public int $playerNumber,
        public string $position,
    ) {
    }
}
