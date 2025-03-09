<?php

namespace App\Presentation\Mapper\Team;

use App\Domain\Team\Model\Team;
use App\Presentation\DTO\Response\Team\TeamPlayerResponseDTO;
use App\Presentation\DTO\Response\Team\TeamResponseDTO;
use AutoMapperPlus\AutoMapperInterface;
use AutoMapperPlus\AutoMapperPlusBundle\AutoMapperConfiguratorInterface;
use AutoMapperPlus\Configuration\AutoMapperConfigInterface;

class TeamToTeamResponseDTOMapping implements AutoMapperConfiguratorInterface
{
    public function configure(AutoMapperConfigInterface $config): void
    {
        $config->registerMapping(Team::class, TeamResponseDTO::class)
            ->forMember('teamId', fn (Team $team) => $team->getTeamId()->value())
        ->forMember('players', function (Team $team, AutoMapperInterface $mapper): array {
            return $mapper->mapMultiple($team->getPlayers(), TeamPlayerResponseDTO::class);
        });
    }
}
