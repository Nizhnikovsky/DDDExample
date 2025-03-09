<?php

namespace App\Infrastructure\Doctrine\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
#[\Attribute] class UniqueEntityConstraint extends Constraint
{
    public string $message = 'The value "{{ value }}" is already used.';
    public string $entityClass;
    public string $field;

    public function __construct(string $entityClass, string $field, ?string $message = null, ?array $groups = null, mixed $payload = null)
    {
        parent::__construct([], $groups, $payload);
        $this->entityClass = $entityClass;
        $this->field = $field;
        if ($message) {
            $this->message = $message;
        }
    }

    public function validatedBy(): string
    {
        return UniqueEntityValidator::class;
    }

    public function getTargets(): string
    {
        return self::PROPERTY_CONSTRAINT;
    }
}
