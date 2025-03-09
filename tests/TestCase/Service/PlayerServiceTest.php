<?php


namespace App\Tests\TestCase\Service;

use App\Domain\Player\DTO\PlayerDTO;
use App\Domain\Player\Enum\PlayerPositionEnum;
use App\Domain\Player\Model\Player;
use App\Domain\Player\Model\PlayerTeam;
use App\Domain\Player\Repository\PlayerRepository;
use App\Domain\Player\Service\PlayerService;
use App\Shared\Exception\PlayerNotFoundException;
use App\Shared\ValueObjects\Uuid;
use App\Domain\Player\ValueObject\AgeValue;
use App\Domain\Player\ValueObject\NumberValue;
use App\Domain\Player\ValueObject\PositionValue;
use PHPUnit\Framework\TestCase;

class PlayerServiceTest extends TestCase
{
    private PlayerRepository $playerRepository;
    private PlayerService $playerService;
    protected function setUp(): void
    {
        $this->playerRepository = $this->createMock(PlayerRepository::class);
        $this->playerService = new PlayerService($this->playerRepository);
    }

    public function testGetPlayerById(): void
    {
        $teamId = new Uuid();
        $team = new PlayerTeam($teamId, 'FC Barcelona');
        $playerId = new Uuid();
        $player = new Player(
            $playerId,
            'Leo',
            'Messi',
            new AgeValue(36),
            new NumberValue(11),
            new PositionValue(PlayerPositionEnum::Forward),
            $team,
            new \DateTimeImmutable('2009-01-20'),
        );

        $this->playerRepository->expects($this->once())
            ->method('getPlayer')
            ->with($playerId)
            ->willReturn($player);

        $result = $this->playerService->getPlayer($playerId);

        $this->assertSame($player, $result);
    }

    public function testGetPlayerByIdNotFound()
    {
        $this->playerRepository->expects($this->once())
            ->method('getPlayer')
            ->with('0195715a-83dd-75da-85b3-8ff975356745')
            ->willThrowException(new PlayerNotFoundException('0195715a-83dd-75da-85b3-8ff975356745'));

        $this->expectException(PlayerNotFoundException::class);
        $this->expectExceptionMessage('Player with ID - 0195715a-83dd-75da-85b3-8ff975356745 not found');

        $this->playerService->getPlayer(new Uuid('0195715a-83dd-75da-85b3-8ff975356745'));
    }

    public function testCreatePlayer(): void
    {
        $createPlayerDTO = new PlayerDTO('Leo', 'Messi', 36, 11, 'forward');
        $team = new PlayerTeam(new Uuid(), 'FC Barcelona');
        $player = new Player(
            new Uuid(),
            'Leo',
            'Messi',
            new AgeValue(36),
            new NumberValue(11),
            new PositionValue(PlayerPositionEnum::Forward),
            $team,
            new \DateTimeImmutable(),
        );

        $this->playerRepository->expects($this->once())
            ->method('createPlayer')
            ->with($this->isInstanceOf(Player::class));

        $result = $this->playerService->createPlayer($createPlayerDTO, $team);

        $this->assertEquals($player->getFirstName(), $result->getFirstName());
        $this->assertEquals($player->getLastName(), $result->getLastName());
        $this->assertEquals($player->getAge(), $result->getAge());
        $this->assertEquals($player->getPosition(), $result->getPosition());
        $this->assertEquals($player->getPlayerNumber(), $result->getPlayerNumber());
        $this->assertEquals($player->getTeam()->getTeamName(), $result->getTeam()->getTeamName());
    }
}