<?php


namespace App\Presentation\Mapper\Team;

use App\Domain\Team\DTO\TeamDTO;
use App\Presentation\DTO\Request\Team\CreateTeamDTO;
use AutoMapperPlus\AutoMapperPlusBundle\AutoMapperConfiguratorInterface;
use AutoMapperPlus\Configuration\AutoMapperConfigInterface;

class CreateTeamDTOToDomainTeamDTOMapping implements AutoMapperConfiguratorInterface
{

    public function configure(AutoMapperConfigInterface $config): void
    {
        $config->registerMapping(CreateTeamDTO::class, TeamDTO::class);
    }
}