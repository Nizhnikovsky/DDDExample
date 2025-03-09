<?php


namespace App\Presentation\Controller\Team;

use App\Domain\Team\Service\TeamService;
use App\Presentation\DTO\Response\Team\TeamResponseDTO;
use App\Shared\Abstractions\RestController;
use App\Shared\Dictionary\ValidationRegExpMask;
use App\Shared\ValueObjects\Uuid;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route(
    path: '/api/v1/team/{teamId}',
    name: 'get_team',
    requirements: ['teamId' => ValidationRegExpMask::UUID_MASK],
    methods: [Request::METHOD_GET]
)]
class GetTeamAction extends RestController
{
    public function __construct(
        private readonly TeamService $teamService
    ){}

    public function __invoke(string $teamId)
    {
        $team = $this->teamService->getTeam(new Uuid($teamId));

        return $this->response($this->mapper->map($team, TeamResponseDTO::class));
    }
}