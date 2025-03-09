<?php

namespace App\Presentation\Controller\Player;

use App\Domain\Player\Service\PlayerService;
use App\Shared\Abstractions\RestController;
use App\Shared\Dictionary\ValidationRegExpMask;
use App\Shared\ValueObjects\Uuid;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route(
    path: '/api/v1/player/{playerId}/delete',
    name: 'delete_player',
    requirements: ['playerId' => ValidationRegExpMask::UUID_MASK],
    methods: [Request::METHOD_DELETE]
)]
class DeletePayerAction extends RestController
{
    public function __construct(
        private readonly PlayerService $playerService,
    ) {
    }

    public function __invoke(string $playerId): JsonResponse
    {
        $this->playerService->deletePlayer(new Uuid($playerId));

        return $this->success();
    }
}
