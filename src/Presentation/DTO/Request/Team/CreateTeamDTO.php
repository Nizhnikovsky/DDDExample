<?php

namespace App\Presentation\DTO\Request\Team;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

readonly class CreateTeamDTO
{
    public function __construct(
        #[Assert\NotBlank(message: 'Team name is required')]
        #[Assert\Length(
            min: 2,
            max: 100,
            minMessage: 'Team name must be at least {{ limit }} characters long',
            maxMessage: 'Team name cannot be longer than {{ limit }} characters',
        )]
        public string $name,
        #[Assert\NotBlank(message: 'City is required')]
        #[Assert\Length(
            min: 2,
            max: 100,
            minMessage: 'City must be at least {{ limit }} characters long',
            maxMessage: 'City cannot be longer than {{ limit }} characters',
        )]
        public string $city,
        #[Assert\NotBlank(message: 'Stadium is required')]
        #[Assert\Length(
            min: 2,
            max: 100,
            minMessage: 'Stadium name must be at least {{ limit }} characters long',
            maxMessage: 'Stadium name cannot be longer than {{ limit }} characters',
        )]
        public string $stadium,

        #[Assert\NotBlank(message: 'Year cannot be empty.')]
        #[Assert\Type(type: 'integer', message: 'Year must be a valid number.')]
        public int $yearFounded,
    ) {
    }

    #[Assert\Callback]
    public function validateYear(ExecutionContextInterface $context): void
    {
        $currentYear = (int) date('Y');
        if ($this->yearFounded < 1800 || $this->yearFounded > $currentYear) {
            $context->buildViolation("Year must be between 1800 and $currentYear.")
                ->atPath('yearFounded')
                ->addViolation();
        }
    }
}
