<?php

namespace App\Presentation\Controller\Player;

use App\Domain\Player\Service\PlayerService;
use App\Presentation\DTO\Response\Player\PlayerResponseDTO;
use App\Shared\Abstractions\RestController;
use App\Shared\Dictionary\ValidationRegExpMask;
use App\Shared\ValueObjects\Uuid;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route(
    path: '/api/v1/player/{playerId}',
    name: 'get_player',
    requirements: ['playerId' => ValidationRegExpMask::UUID_MASK],
    methods: [Request::METHOD_GET]
)]
class GetPlayerAction extends RestController
{
    public function __construct(
        private readonly PlayerService $playerService,
    ) {
    }

    public function __invoke(string $playerId): JsonResponse
    {
        $player = $this->playerService->getPlayer(new Uuid($playerId));

        return $this->response($this->mapper->map($player, PlayerResponseDTO::class));
    }
}
