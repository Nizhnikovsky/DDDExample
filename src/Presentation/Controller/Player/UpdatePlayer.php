<?php

namespace App\Presentation\Controller\Player;

use App\Domain\Player\DTO\UpdatePlayerDTO as DomainUpdatePlayerDTO;
use App\Domain\Player\Service\PlayerService;
use App\Presentation\DTO\Request\Player\UpdatePlayerDTO;
use App\Presentation\DTO\Response\Player\PlayerResponseDTO;
use App\Shared\Abstractions\RestController;
use App\Shared\Dictionary\ValidationRegExpMask;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;

#[Route(
    path: '/api/v1/player/{playerId}',
    name: 'update_player',
    requirements: ['playerId' => ValidationRegExpMask::UUID_MASK],
    methods: [Request::METHOD_PUT]
)]
class UpdatePlayer extends RestController
{
    public function __construct(
        private readonly PlayerService $playerService,
    ) {
    }

    public function __invoke(#[MapRequestPayload] UpdatePlayerDTO $playerDTO, string $playerId): JsonResponse
    {
        $updateDto = $this->mapper->map($playerDTO, DomainUpdatePlayerDTO::class, ['playerId' => $playerId]);
        $player = $this->playerService->updatePlayer($updateDto);

        return $this->response($this->mapper->map($player, PlayerResponseDTO::class));
    }
}
