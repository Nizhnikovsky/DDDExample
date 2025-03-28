<?php

namespace App\Domain\Team\Repository;

use App\Domain\Team\Model\Team;
use App\Shared\Exception\TeamNotFoundException;
use App\Shared\ValueObjects\Uuid;

interface TeamRepository
{
    /**
     * @throws TeamNotFoundException
     */
    public function getTeam(Uuid $teamId): Team;

    public function createTeam(Team $team): Team;

    public function updateTeam(Team $team): Team;

    public function deleteTeam(Uuid $teamId): void;
}
