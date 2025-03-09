<?php


namespace App\Presentation\Mapper\Team;

use App\Domain\Team\Model\TeamPlayer;
use App\Presentation\DTO\Response\Team\TeamPlayerResponseDTO;
use AutoMapperPlus\AutoMapperPlusBundle\AutoMapperConfiguratorInterface;
use AutoMapperPlus\Configuration\AutoMapperConfigInterface;

class TeamPlayerToTeamPlayerResponseDTOMapping implements AutoMapperConfiguratorInterface
{
    public function configure(AutoMapperConfigInterface $config): void
    {
        $config->registerMapping(TeamPlayer::class, TeamPlayerResponseDTO::class)
            ->forMember('playerId', fn(TeamPlayer $teamPlayer): string => $teamPlayer->getPlayerId()->value());
    }
}