<?php


namespace App\Domain\Player\ValueObject;

use App\Shared\Exception\ValueValidationException;

readonly class AgeValue
{
    /**
     * @throws ValueValidationException
     */
    public function __construct(
        private int $value
    ){
        $this->validate($this->value);
    }

    /**
     * @return int
     */
    public function getValue(): int
    {
        return $this->value;
    }

    /**
     * @throws ValueValidationException
     */
    private function validate(int $age): void
    {
        if ($age < 16 || $age > 45) {
            throw new ValueValidationException('Player must be between 16 and 45');
        }
    }
}