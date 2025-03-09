<?php

declare(strict_types=1);

namespace App\Presentation\Controller\Player;

use App\Domain\Player\DTO\PlayerDTO;
use App\Domain\Player\Model\PlayerTeam;
use App\Domain\Player\Service\PlayerService;
use App\Domain\Team\Service\TeamService;
use App\Presentation\DTO\Request\Player\CreatePlayerDTO;
use App\Presentation\DTO\Response\Player\PlayerResponseDTO;
use App\Shared\Abstractions\RestController;
use App\Shared\Exception\TeamFullException;
use App\Shared\ValueObjects\Uuid;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;

#[Route(
    path: '/api/v1/player/create',
    name: 'create_player',
    methods: [Request::METHOD_POST]
)]
class CreatePlayerAction extends RestController
{
    public function __construct(
        private readonly PlayerService $playerService,
        private readonly TeamService $teamService,
    ) {
    }

    public function __invoke(#[MapRequestPayload] CreatePlayerDTO $playerDTO): JsonResponse
    {
        $team = $this->teamService->getTeam(new Uuid($playerDTO->teamId));
        if ($team->isFull()) {
            throw new TeamFullException($team->getName());
        }

        $playerDomainDTO = $this->mapper->map($playerDTO, PlayerDTO::class);
        $player = $this->playerService->createPlayer($playerDomainDTO, new PlayerTeam($team->getTeamId(), $team->getName()));

        return $this->response($this->mapper->map($player, PlayerResponseDTO::class));
    }
}
