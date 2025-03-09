<?php


namespace App\Presentation\Controller\Team;

use App\Domain\Team\Service\TeamService;
use App\Presentation\DTO\Request\Team\CreateTeamDTO;
use App\Presentation\DTO\Response\Team\TeamResponseDTO;
use App\Shared\Abstractions\RestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;
use App\Domain\Team\DTO\TeamDTO as DomainTeamDTO;

#[Route(
    path: '/api/v1/team/create',
    name: 'create_team',
    methods: [Request::METHOD_POST]
)]
class CreateTeamAction extends RestController
{
    public function __construct(
        private readonly TeamService $teamService
    ){}

    public function __invoke(#[MapRequestPayload] CreateTeamDTO $teamDTO)
    {
        $domainTeamDTO = $this->mapper->map($teamDTO, DomainTeamDTO::class);
        $team = $this->teamService->createTeam($domainTeamDTO);

        return $this->response($this->mapper->map($team, TeamResponseDTO::class));
    }
}