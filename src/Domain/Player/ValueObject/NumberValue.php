<?php


namespace App\src\Domain\Player\ValueObject;

use App\Shared\Exception\ValueValidationException;

readonly class NumberValue
{
    /**
     * @throws ValueValidationException
     */
    public function __construct(private int $number)
    {
        $this->validateValue($this->number);
    }

    public function getValue(): int
    {
        return $this->number;
    }

    /**
     * @throws ValueValidationException
     */
    private function validateValue(int $number): void
    {
        if ($number < 1 || $number > 99) {
            throw new ValueValidationException('Player number should be positive and not greater than 99');
        }
    }
}