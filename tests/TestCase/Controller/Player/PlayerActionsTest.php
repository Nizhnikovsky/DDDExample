<?php


namespace App\Tests\TestCase\Controller\Player;


use App\Domain\Player\DTO\PlayerDTO;
use App\Domain\Player\DTO\UpdatePlayerDTO;
use App\Domain\Player\Enum\PlayerPositionEnum;
use App\Domain\Player\Model\Player;
use App\Domain\Player\Model\PlayerTeam;
use App\Domain\Player\Service\PlayerService;
use App\Domain\Team\Model\Team;
use App\Domain\Team\Service\TeamService;
use App\Shared\Exception\PlayerNotFoundException;
use App\Shared\ValueObjects\Uuid;
use App\Domain\Player\ValueObject\AgeValue;
use App\Domain\Player\ValueObject\NumberValue;
use App\Domain\Player\ValueObject\PositionValue;
use App\Infrastructure\Doctrine\Player\Repository\PlayerRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class PlayerActionsTest extends WebTestCase
{
    private $client;
    private PlayerService $playerServiceMock;
    private TeamService $teamServiceMock;
    private PlayerRepository $playerRepositoryMock;

    protected function setUp(): void
    {
        parent::setUp();
        self::ensureKernelShutdown();
        $this->client = static::createClient();
        $container = self::getContainer();
        $this->playerServiceMock = $this->createMock(PlayerService::class);
        $this->teamServiceMock = $this->createMock(TeamService::class);
        $this->playerRepositoryMock = $this->createMock(PlayerRepository::class);
        $container->set(PlayerService::class, $this->playerServiceMock);
        $container->set(TeamService::class, $this->teamServiceMock);
        $container->set(PlayerRepository::class, $this->playerRepositoryMock);
    }

    public function testGetPlayerIsWrong()
    {
        $this->client->request('GET', '/api/v1/player/999');
        $response = $this->client->getResponse();

        $this->assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());
    }

    public function testGetPlayerNotFound()
    {
        $this->playerServiceMock->expects($this->once())
            ->method('getPlayer')
            ->with(new Uuid('0195715a-83dd-75da-85b3-8ff975356895'))
            ->willThrowException(new PlayerNotFoundException('0195715a-83dd-75da-85b3-8ff975356895'));

        $this->client->request('GET', '/api/v1/player/0195715a-83dd-75da-85b3-8ff975356895');
        $response = $this->client->getResponse();

        $this->assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());
    }

    public function testGetPlayer()
    {
        $playerData = file_get_contents(__DIR__ . '/../../../DataFixtures/player_response.json');

        $this->playerServiceMock->expects($this->once())
            ->method('getPlayer')
            ->with(new Uuid('0195715a-83dd-75da-85b3-8ff975356616'))
            ->willReturn($this->makePlayer());

        $this->client->request('GET', '/api/v1/player/0195715a-83dd-75da-85b3-8ff975356616');
        /** @var JsonResponse $response */
        $response = $this->client->getResponse();

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(json_decode($playerData, true), json_decode($response->getContent(), true));
    }

    public function testCreatePlayer()
    {
        $playerData = file_get_contents(__DIR__ . '/../../../DataFixtures/player_response.json');

        $this->playerRepositoryMock->expects($this->exactly(2))
            ->method('findOneBy')
            ->willReturnMap([
                [['lastName' => 'Oblak'], null],
                [['playerNumber' => 1], null],
            ]);

        $this->teamServiceMock->expects($this->once())
            ->method('getTeam')
            ->with(new Uuid('01957109-b207-7488-a995-24af7d598730'))
            ->willReturn($this->makeTeam());

        $this->playerServiceMock->expects($this->once())
            ->method('createPlayer')
            ->with($this->makePlayerDTO(), new PlayerTeam($this->makeTeam()->getTeamId(), $this->makeTeam()->getName()))
            ->willReturn($this->makePlayer());

        $this->client->request('POST', '/api/v1/player/create', [], [], [
            'CONTENT_TYPE' => 'application/json',
        ], json_encode([
            'firstName' => 'Jan',
            'lastName' => 'Oblak',
            'age' => 32,
            'playerNumber' => 1,
            'position' => 'goalkeeper',
            'teamId' => '01957109-b207-7488-a995-24af7d598730'
        ]));

        $response = $this->client->getResponse();

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(json_decode($playerData, true), json_decode($response->getContent(), true));
    }

    public function testCreatePlayerValidationFalls()
    {
        $this->playerRepositoryMock->expects($this->exactly(2))
            ->method('findOneBy')
            ->willReturnMap([
                [['lastName' => 'Oblak'], null],
                [['playerNumber' => 1], null],
            ]);

        $this->client->request('POST', '/api/v1/player/create', [], [], [
            'CONTENT_TYPE' => 'application/json',
        ], json_encode([
            'firstName' => 'Jan',
            "lastName" => 'Oblak',
            "age" => 4,
            "playerNumber" => 145,
            "position" => "ballboy",
            'teamId' => '01957109-b207-7488-a995-24af7d598730'
        ]));

        $response = $this->client->getResponse();
        $body = json_decode($response->getContent(), true);

        $this->assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY, $response->getStatusCode());
        $this->assertEquals($body['error'], 'Validation failed');
        $this->assertEquals($body['validation_errors'], [
            "age" =>"The player age must be between 14 and 45.",
            "playerNumber" =>"This value should be less than or equal to 99.",
            "position" =>"Invalid option selected."
        ]);
    }

    public function testDeletePlayer()
    {
        $this->playerServiceMock->expects($this->once())
            ->method('deletePlayer')
            ->with(new Uuid('0195715a-83dd-75da-85b3-8ff975356616'));

        $this->client->request('DELETE', '/api/v1/player/0195715a-83dd-75da-85b3-8ff975356616/delete');
        $response = $this->client->getResponse();

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testUpdatePlayer()
    {
        $teamData = file_get_contents(__DIR__ . '/../../../DataFixtures/player_updated_response.json');

        $this->playerServiceMock->expects($this->once())
            ->method('updatePlayer')
            ->with($this->makeUpdatePlayerDTO(age: 35, playerNumber: 24))
            ->willReturn($this->makePlayer(age: 35, playerNumber: 24));

        $this->client->request('PUT', '/api/v1/player/0195715a-83dd-75da-85b3-8ff975356616', [], [], [
            'CONTENT_TYPE' => 'application/json',
        ], json_encode([
            'age' => 35,
            'playerNumber' => 24,
            'position' => PlayerPositionEnum::Goalkeeper->value
        ]));

        $response = $this->client->getResponse();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(json_decode($teamData, true), json_decode($response->getContent(), true));
    }

    private function makePlayer(int $age = null, int $playerNumber = null, string $position = null): Player
    {
        $playerData = file_get_contents(__DIR__ . '/../../../DataFixtures/player_response.json');
        $playerData = json_decode($playerData, true);

        $playerTeam = new PlayerTeam(
            new Uuid($playerData['team']['id']),
            $playerData['team']['name']
        );

        return new Player(
            new Uuid($playerData['playerId']),
            $playerData['firstName'],
            $playerData['lastName'],
            new AgeValue($age ?? $playerData['age']),
            new NumberValue($playerNumber ??$playerData['playerNumber']),
            new PositionValue(PlayerPositionEnum::from($position ?? $playerData['position'])),
            $playerTeam,
            new \DateTimeImmutable($playerData['joinedAt'])
        );
    }

    private function makeTeam(): Team
    {
        $teamData = file_get_contents(__DIR__ . '/../../../DataFixtures/team_response.json');
        $teamData = json_decode($teamData, true);

        return new Team(
            new Uuid($teamData['teamId']),
            $teamData['name'],
            $teamData['yearFounded'],
            $teamData['stadium'],
            $relocateCity ?? $teamData['city'],
        );
    }

    private function makePlayerDTO(): PlayerDTO
    {
        $playerData = file_get_contents(__DIR__ . '/../../../DataFixtures/player_response.json');
        $playerData = json_decode($playerData, true);

        return new PlayerDTO(
            $playerData['firstName'],
            $playerData['lastName'],
            $playerData['age'],
            $playerData['playerNumber'],
            $playerData['position'],
        );
    }

    private function makeUpdatePlayerDTO(int $age = null, int $playerNumber = null, string $position = null)
    {
        $playerData = file_get_contents(__DIR__ . '/../../../DataFixtures/player_response.json');
        $playerData = json_decode($playerData, true);

        return new UpdatePlayerDTO(
            new Uuid($playerData['playerId']),
            $age ?? $playerData['age'],
            $playerNumber ?? $playerData['playerNumber'],
            $position ?? $playerData['position'],
        );
    }
}
