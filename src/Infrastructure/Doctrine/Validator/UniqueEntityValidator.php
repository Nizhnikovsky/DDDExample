<?php


namespace App\Infrastructure\Doctrine\Validator;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class UniqueEntityValidator extends ConstraintValidator
{
    public function __construct(private EntityManagerInterface $entityManager) {}

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof UniqueEntityConstraint) {
            throw new UnexpectedTypeException($constraint, UniqueEntityConstraint::class);
        }

        if ($value === null || $value === '') {
            return; // Allow empty values (use NotBlank for required validation)
        }

        // Get Repository and check if the value exists
        $repository = $this->entityManager->getRepository($constraint->entityClass);
        $existingEntity = $repository->findOneBy([$constraint->field => $value]);

        if ($existingEntity) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $value)
                ->addViolation();
        }
    }
}
