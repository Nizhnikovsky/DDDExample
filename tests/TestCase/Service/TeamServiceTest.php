<?php


namespace App\Tests\TestCase\Service;

use App\Domain\Team\DTO\TeamDTO;
use App\Domain\Team\Model\Team;
use App\Domain\Team\Repository\TeamRepository;
use App\Domain\Team\Service\TeamService;
use App\Shared\Exception\TeamNotFoundException;
use App\Shared\ValueObjects\Uuid;
use JetBrains\PhpStorm\NoReturn;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class TeamServiceTest extends TestCase
{
    private $teamRepositoryMock;
    private $teamService;

    protected function setUp(): void
    {
        $this->teamRepositoryMock = $this->createMock(TeamRepository::class);
        $eventDispatcher = $this->createMock(EventDispatcherInterface::class);
        $this->teamService = new TeamService($this->teamRepositoryMock, $eventDispatcher);
    }

    public function testGetTeamById(): void
    {
        $teamId = new Uuid();
        $team = new Team($teamId, 'FC Barcelona', 1923, 'Camp Nou', 'Barcelona', []);

        $this->teamRepositoryMock->expects($this->once())
            ->method('getTeam')
            ->with($teamId)
            ->willReturn($team);

        $result = $this->teamService->getTeam($teamId);

        $this->assertSame($team, $result);
    }

    #[NoReturn] public function testCreateTeam()
    {
        $createTeamDTO = new TeamDTO('FC Barcelona', 1923, 'Camp Nou', 'Barcelona');
        $team = new Team(new Uuid(), 'FC Barcelona', 1923, 'Camp Nou', 'Barcelona', []);

        $this->teamRepositoryMock->expects($this->once())
            ->method('createTeam')
            ->with($this->isInstanceOf(Team::class));

        $result = $this->teamService->createTeam($createTeamDTO);

        $this->assertEquals($team->getName(), $result->getName());
        $this->assertEquals($team->getYearFounded(), $result->getYearFounded());
        $this->assertEquals($team->getStadium(), $result->getStadium());
        $this->assertEquals($team->getCity(), $result->getCity());
    }

    public function testGetTeamByIdNotFound()
    {
        $this->teamRepositoryMock->expects($this->once())
            ->method('getTeam')
            ->with('0195715a-83dd-75da-85b3-8ff975356745')
            ->willThrowException(new TeamNotFoundException('0195715a-83dd-75da-85b3-8ff975356745'));

        $this->expectException(TeamNotFoundException::class);
        $this->expectExceptionMessage('Team with ID - 0195715a-83dd-75da-85b3-8ff975356745 not found');

        $this->teamService->getTeam(new Uuid('0195715a-83dd-75da-85b3-8ff975356745'));
    }


}