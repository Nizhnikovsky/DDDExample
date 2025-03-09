<?php

namespace App\Presentation\Mapper\Player;

use App\Domain\Player\DTO\UpdatePlayerDTO as DomainUpdatePlayerDTO;
use App\Presentation\DTO\Request\Player\UpdatePlayerDTO;
use App\Shared\ValueObjects\Uuid;
use AutoMapperPlus\AutoMapperInterface;
use AutoMapperPlus\AutoMapperPlusBundle\AutoMapperConfiguratorInterface;
use AutoMapperPlus\Configuration\AutoMapperConfigInterface;

class UpdatePlayerDTOToDomainUpdatePlayerDTOMapping implements AutoMapperConfiguratorInterface
{
    public function configure(AutoMapperConfigInterface $config): void
    {
        $config->registerMapping(UpdatePlayerDTO::class, DomainUpdatePlayerDTO::class)
            ->forMember(
                'id',
                function (UpdatePlayerDTO $playerDTO, AutoMapperInterface $mapper, array $context) {
                    if (!$context['playerId']) {
                        throw new \Exception('missed playerId value');
                    }

                    return new Uuid($context['playerId']);
                });
    }
}
