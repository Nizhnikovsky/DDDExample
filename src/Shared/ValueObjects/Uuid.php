<?php


namespace App\Shared\ValueObjects;

use App\Shared\Exception\ValueValidationException;
use Symfony\Component\Uid\Uuid as SymfonyUuid;

class Uuid
{
    private string $value;

    /**
     * @throws ValueValidationException
     */
    public function __construct(string|null $value = null)
    {
        if (!$value) {
            $this->value = SymfonyUuid::v7()->toString();
        } elseif (!SymfonyUuid::isValid($value)) {
            throw new ValueValidationException("Invalid UUID format.");
        } else {
            $this->value = $value;
        }
    }

    public function value(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}