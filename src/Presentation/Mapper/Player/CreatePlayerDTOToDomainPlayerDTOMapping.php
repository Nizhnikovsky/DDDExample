<?php

namespace App\Presentation\Mapper\Player;

use App\Domain\Player\DTO\PlayerDTO;
use App\Presentation\DTO\Request\Player\CreatePlayerDTO;
use AutoMapperPlus\AutoMapperPlusBundle\AutoMapperConfiguratorInterface;
use AutoMapperPlus\Configuration\AutoMapperConfigInterface;

class CreatePlayerDTOToDomainPlayerDTOMapping implements AutoMapperConfiguratorInterface
{
    public function configure(AutoMapperConfigInterface $config): void
    {
        $config->registerMapping(CreatePlayerDTO::class, PlayerDTO::class);
    }
}
