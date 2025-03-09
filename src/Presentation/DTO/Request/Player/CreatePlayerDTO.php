<?php


namespace App\Presentation\DTO\Request\Player;

use App\Domain\Player\Enum\PlayerPositionEnum;
use App\Infrastructure\Doctrine\Entity\Player;
use App\Infrastructure\Doctrine\Validator\UniqueEntityConstraint;
use Symfony\Component\Validator\Constraints as Assert;

readonly class CreatePlayerDTO
{
    public function __construct(
        #[Assert\NotBlank(message: 'Player first name is required')]
        #[Assert\Length(
            min: 2,
            max: 100,
            minMessage: 'Player first name must be at least {{ limit }} characters long',
            maxMessage: 'Player first name cannot be longer than {{ limit }} characters',
        )]
        public string $firstName,

        #[Assert\NotBlank(message: 'Player last name is required')]
        #[Assert\Length(
            min: 2,
            max: 100,
            minMessage: 'Player last name must be at least {{ limit }} characters long',
            maxMessage: 'Player last name cannot be longer than {{ limit }} characters',
        )]
        #[UniqueEntityConstraint(entityClass: Player::class, field: "lastName", message: "This last name is already taken.")]
        public string $lastName,

        #[Assert\NotBlank(message: 'Player age is required')]
        #[Assert\Range(notInRangeMessage: 'The player age must be between {{ min }} and {{ max }}.', min: 14, max: 45)]
        public int $age,

        #[Assert\NotBlank(message: 'Player number is required')]
        #[Assert\LessThanOrEqual(99), Assert\Positive]
        #[UniqueEntityConstraint(entityClass: Player::class, field: "playerNumber", message: "This player number is already taken.")]
        public int $playerNumber,

        #[Assert\NotBlank(message: 'Player position is required')]
        #[Assert\Choice(callback: [PlayerPositionEnum::class, 'values'], message: 'Invalid option selected.')]
        public string $position,

        #[Assert\NotBlank(message: 'Player team is required')]
        #[Assert\Uuid()]
        public string $teamId,
    ){
    }
}