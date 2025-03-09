<?php


namespace App\Domain\Player\Service;

use App\Domain\Player\DTO\PlayerDTO;
use App\Domain\Player\DTO\UpdatePlayerDTO;
use App\Domain\Player\Enum\PlayerPositionEnum;
use App\Domain\Player\Model\Player;
use App\Domain\Player\Model\PlayerTeam;
use App\Domain\Player\Repository\PlayerRepository;
use App\Shared\Exception\PlayerNotFoundException;
use App\Shared\Exception\ValueValidationException;
use App\Shared\ValueObjects\Uuid;
use App\src\Domain\Player\ValueObject\AgeValue;
use App\src\Domain\Player\ValueObject\NumberValue;
use App\src\Domain\Player\ValueObject\PositionValue;

class PlayerService
{
    public function __construct(
        private readonly PlayerRepository $playerRepository
    ){
    }

    /**
     * @throws ValueValidationException
     */
    public function createPlayer(PlayerDTO $player, PlayerTeam $team): Player
    {
        $player = new Player(
            new Uuid(),
            $player->firstName,
            $player->lastName,
            new AgeValue($player->age),
            new NumberValue($player->playerNumber),
            new PositionValue(PlayerPositionEnum::from($player->position)),
            $team,
            new \DateTimeImmutable()
        );

        $this->playerRepository->createPlayer($player);

        return $player;
    }


    /**
     * @throws ValueValidationException
     * @throws PlayerNotFoundException
     */
    public function getPlayer(Uuid $playerId): Player
    {
        return $this->playerRepository->getPlayer($playerId);
    }

    /**
     * @throws ValueValidationException
     * @throws PlayerNotFoundException
     */
    public function updatePlayer(UpdatePlayerDTO $playerDTO): Player
    {
        $player = $this->playerRepository->getPlayer($playerDTO->id);
        $player->changePlayerNumber(new NumberValue($playerDTO->playerNumber));
        $player->changePlayerPosition(new PositionValue(PlayerPositionEnum::from($playerDTO->position)));
        $player->changePlayerAge(new AgeValue($playerDTO->age));

        return $this->playerRepository->updatePlayer($player);
    }

    public function deletePlayer(Uuid $playerId): void
    {
        $this->playerRepository->deletePlayer($playerId);
    }
}