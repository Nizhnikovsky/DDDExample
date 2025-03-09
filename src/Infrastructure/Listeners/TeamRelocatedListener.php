<?php

namespace App\Infrastructure\Listeners;

use App\Domain\Team\Event\TeamRelocatedEvent;
use App\Infrastructure\Doctrine\Player\Repository\PlayerRepository;
use Monolog\Attribute\WithMonologChannel;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener(event: TeamRelocatedEvent::class)]
#[WithMonologChannel('player_event')]
class TeamRelocatedListener
{
    public function __construct(
        private readonly LoggerInterface $playerLogger,
        private readonly PlayerRepository $playerRepository,
    ) {
    }

    public function __invoke(TeamRelocatedEvent $event): void
    {
        $teamPlayers = $this->playerRepository->getPlayersByTeamId($event->getTeamId());
        foreach ($teamPlayers as $player) {
            $this->playerLogger->info(sprintf(
                'Notification to Player %s: Your team %s has been relocated from %s to %s',
                sprintf('%s %s', $player['firstName'], $player['lastName']),
                $event->getTeamName(),
                $event->getOldCity(),
                $event->getNewCity()
            ));
        }
    }
}
