<?php

namespace App\Presentation\Mapper\Player;

use App\Domain\Player\Model\Player;
use App\Presentation\DTO\Response\Player\PlayerResponseDTO;
use App\Presentation\DTO\Response\Player\PlayerTeamResponseDTO;
use AutoMapperPlus\AutoMapperPlusBundle\AutoMapperConfiguratorInterface;
use AutoMapperPlus\Configuration\AutoMapperConfigInterface;

class PlayerToPlayerResponseDTOMapping implements AutoMapperConfiguratorInterface
{
    public function configure(AutoMapperConfigInterface $config): void
    {
        $config->registerMapping(Player::class, PlayerResponseDTO::class)
            ->forMember('playerId', fn (Player $player): string => $player->getPlayerId()->value())
            ->forMember('playerNumber', fn (Player $player): int => $player->getPlayerNumber()->getValue())
            ->forMember('age', fn (Player $player): int => $player->getAge()->getValue())
            ->forMember('position', fn (Player $player): string => $player->getPosition()->getPosition()->value)
            ->forMember('joinedAt', fn (Player $player): string => $player->getJoinedAt()->format('F j, Y'))
            ->forMember(
                'team',
                fn (Player $player): PlayerTeamResponseDTO => new PlayerTeamResponseDTO(
                    $player->getTeam()->getTeamId()->value(),
                    $player->getTeam()->getTeamName()
                )
            );
    }
}
