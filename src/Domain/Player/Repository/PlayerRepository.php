<?php

namespace App\Domain\Player\Repository;

use App\Domain\Player\Model\Player;
use App\Shared\Exception\PlayerNotFoundException;
use App\Shared\Exception\ValueValidationException;
use App\Shared\ValueObjects\Uuid;

interface PlayerRepository
{
    /**
     * @throws ValueValidationException
     * @throws PlayerNotFoundException
     */
    public function getPlayer(Uuid $playerId): ?Player;

    public function createPlayer(Player $player): Player;

    public function updatePlayer(Player $player): Player;

    public function deletePlayer(Uuid $playerId): void;
}
