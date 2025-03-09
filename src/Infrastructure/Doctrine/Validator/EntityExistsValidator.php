<?php


namespace App\Infrastructure\Doctrine\Validator;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class EntityExistsValidator extends ConstraintValidator
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof EntityExists) {
            throw new UnexpectedValueException($constraint, EntityExists::class);
        }

        if (empty($value)) {
            return;
        }

        $repository = $this->entityManager->getRepository($constraint->entityClass);
        $entity = $repository->find($value);

        if (!$entity) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ entity }}', $constraint->entityClass)
                ->setParameter('{{ value }}', $value)
                ->addViolation();
        }
    }
}
