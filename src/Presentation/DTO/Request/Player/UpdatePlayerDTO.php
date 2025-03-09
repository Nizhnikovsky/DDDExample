<?php

namespace App\Presentation\DTO\Request\Player;

use App\Domain\Player\Enum\PlayerPositionEnum;
use Symfony\Component\Validator\Constraints as Assert;

class UpdatePlayerDTO
{
    public function __construct(
        #[Assert\LessThanOrEqual(100), Assert\Positive]
        public int $age,

        #[Assert\LessThanOrEqual(99), Assert\Positive]
        public int $playerNumber,

        #[Assert\Choice(callback: [PlayerPositionEnum::class, 'values'], message: 'Invalid option selected.')]
        public string $position,
    ) {
    }
}
