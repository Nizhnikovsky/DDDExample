<?php

namespace App\Presentation\DTO\Request\Team;

use Symfony\Component\Validator\Constraints as Assert;

readonly class RelocateTeamDTO
{
    public function __construct(
        #[Assert\NotBlank(message: 'City is required')]
        #[Assert\Length(
            min: 2,
            max: 100,
            minMessage: 'City name must be at least {{ limit }} characters long',
            maxMessage: 'City name cannot be longer than {{ limit }} characters',
        )]
        public string $city,
    ) {
    }
}
