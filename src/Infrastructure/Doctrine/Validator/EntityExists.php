<?php

namespace App\Infrastructure\Doctrine\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
#[\Attribute] class EntityExists extends Constraint
{
    public string $message = 'The {{ entity }} with ID "{{ value }}" does not exist.';
    public string $entityClass;

    public function __construct(string $entityClass, string $message)
    {
        parent::__construct([]);
        $this->entityClass = $entityClass;
        $this->message = $message;
    }
}
