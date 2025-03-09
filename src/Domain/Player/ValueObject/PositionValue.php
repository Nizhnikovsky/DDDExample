<?php


namespace App\src\Domain\Player\ValueObject;

use App\Domain\Player\Enum\PlayerPositionEnum;
use App\Shared\Exception\ValueValidationException;

readonly class PositionValue
{
    /**
     * @throws ValueValidationException
     */
    public function __construct(private PlayerPositionEnum $position)
    {
        $this->validatePosition($this->position);
    }

    public function getPosition(): PlayerPositionEnum
    {
        return $this->position;
    }

    /**
     * @throws ValueValidationException
     */
    private function validatePosition(PlayerPositionEnum $position): void
    {
        if (!in_array($position, PlayerPositionEnum::cases())) {
            throw new ValueValidationException(
                sprintf('Value should be one of %s', implode(',', PlayerPositionEnum::values()))
            );
        }
    }
}
