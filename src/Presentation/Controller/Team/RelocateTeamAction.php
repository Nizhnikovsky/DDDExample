<?php

namespace App\Presentation\Controller\Team;

use App\Domain\Team\Service\TeamService;
use App\Presentation\DTO\Request\Team\RelocateTeamDTO;
use App\Presentation\DTO\Response\Team\TeamResponseDTO;
use App\Shared\Abstractions\RestController;
use App\Shared\Dictionary\ValidationRegExpMask;
use App\Shared\ValueObjects\Uuid;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;

#[Route(
    path: '/api/v1/team/{teamId}/relocate',
    name: 'relocate_team',
    requirements: ['teamId' => ValidationRegExpMask::UUID_MASK],
    methods: [Request::METHOD_POST]
)]
class RelocateTeamAction extends RestController
{
    public function __construct(
        private readonly TeamService $teamService,
    ) {
    }

    public function __invoke(#[MapRequestPayload] RelocateTeamDTO $teamDTO, string $teamId)
    {
        $team = $this->teamService->relocate(new Uuid($teamId), $teamDTO->city);

        return $this->response($this->mapper->map($team, TeamResponseDTO::class));
    }
}
