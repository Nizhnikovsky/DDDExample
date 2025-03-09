<?php

namespace App\Domain\Team\Service;

use App\Domain\Team\DTO\TeamDTO;
use App\Domain\Team\Model\Team;
use App\Domain\Team\Repository\TeamRepository;
use App\Shared\Exception\TeamNotFoundException;
use App\Shared\ValueObjects\Uuid;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class TeamService
{
    public function __construct(
        private readonly TeamRepository $teamRepository,
        private readonly EventDispatcherInterface $eventDispatcher,
    ) {
    }

    public function createTeam(TeamDTO $teamDTO): Team
    {
        $team = new Team(
            new Uuid(),
            $teamDTO->name,
            $teamDTO->yearFounded,
            $teamDTO->stadium,
            $teamDTO->city
        );

        $this->teamRepository->createTeam($team);

        return $team;
    }

    /**
     * @throws TeamNotFoundException
     */
    public function getTeam(Uuid $teamId): Team
    {
        return $this->teamRepository->getTeam($teamId);
    }

    public function changeTeamStadium(Uuid $teamId, string $stadium): Team
    {
        $team = $this->teamRepository->getTeam($teamId);
        $team->changeStadium($stadium);

        $this->teamRepository->updateTeam($team);

        return $team;
    }

    public function relocate(Uuid $teamId, string $city): Team
    {
        $team = $this->teamRepository->getTeam($teamId);
        $team->relocate($city, $this->eventDispatcher);

        $this->teamRepository->updateTeam($team);

        return $team;
    }

    public function deleteTeam(Uuid $teamId): void
    {
        $this->teamRepository->deleteTeam($teamId);
    }
}
