<?php

namespace App\Domain\Player\Enum;

enum PlayerPositionEnum: string
{
    case Goalkeeper = 'goalkeeper';
    case Defender = 'defender';
    case Midfielder = 'midfielder';
    case Forward = 'forward';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
