<?php


namespace App\Tests\TestCase\Controller\Team;

use App\Domain\Team\DTO\TeamDTO;
use App\Domain\Team\Model\Team;
use App\Domain\Team\Model\TeamPlayer;
use App\Domain\Team\Service\TeamService;
use App\Shared\Exception\TeamNotFoundException;
use App\Shared\ValueObjects\Uuid;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class TeamActionsTest extends WebTestCase
{
    private $client;
    private TeamService $teamServiceMock;

    protected function setUp(): void
    {
        parent::setUp();
        self::ensureKernelShutdown();
        $this->client = static::createClient();
        $container = self::getContainer();
        $this->teamServiceMock = $this->createMock(TeamService::class);
        $container->set(TeamService::class, $this->teamServiceMock);
    }

    public function testGetTeamIdIsWrong()
    {
        $this->client->request('GET', '/api/v1/team/999');
        $response = $this->client->getResponse();

        $this->assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());
    }

    public function testGetTeamNotFound()
    {
        $this->teamServiceMock->expects($this->once())
            ->method('getTeam')
            ->with(new Uuid('01957109-b207-7488-a995-24af7d598789'))
            ->willThrowException(new TeamNotFoundException('01957109-b207-7488-a995-24af7d598789'));

        $this->client->request('GET', '/api/v1/team/01957109-b207-7488-a995-24af7d598789');
        $response = $this->client->getResponse();

        $this->assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());
    }

    public function testGetTeam()
    {
        $teamData = file_get_contents(__DIR__ . '/../../../DataFixtures/team_response.json');

        $this->teamServiceMock->expects($this->once())
            ->method('getTeam')
            ->with(new Uuid('01957109-b207-7488-a995-24af7d598730'))
            ->willReturn($this->makeTeam());

        $this->client->request('GET', '/api/v1/team/01957109-b207-7488-a995-24af7d598730');
        /** @var JsonResponse $response */
        $response = $this->client->getResponse();

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(json_decode($teamData, true), json_decode($response->getContent(), true));
    }

    public function testCreateTeam()
    {
        $teamData = file_get_contents(__DIR__ . '/../../../DataFixtures/team_response.json');

        $this->teamServiceMock->expects($this->once())
            ->method('createTeam')
            ->with($this->makeTeamDTO())
            ->willReturn($this->makeTeam());

        $this->client->request('POST', '/api/v1/team/create', [], [], [
            'CONTENT_TYPE' => 'application/json',
        ], json_encode([
            'name' => 'Atletico de Madrid',
            "yearFounded" => 1903,
            "stadium" => "Riad Air Metropolitano",
            "city" => "Madrid",
        ]));

        $response = $this->client->getResponse();

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(json_decode($teamData, true), json_decode($response->getContent(), true));
    }

    public function testCreateTeamValidationFalls()
    {
        $this->client->request('POST', '/api/v1/team/create', [], [], [
            'CONTENT_TYPE' => 'application/json',
        ], json_encode([
            'name' => 'A',
            "yearFounded" => 1703,
            "stadium" => "Riad Air Metropolitano",
            "city" => "Madrid",
        ]));

        $response = $this->client->getResponse();
        $body = json_decode($response->getContent(), true);

        $this->assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY, $response->getStatusCode());
        $this->assertEquals($body['error'], 'Validation failed');
        $this->assertEquals($body['validation_errors'], [
            "yearFounded" =>"Year must be between 1800 and 2025.",
            "name" =>"Team name must be at least 2 characters long"
        ]);
    }

    public function testDeleteTeam()
    {
        $this->teamServiceMock->expects($this->once())
            ->method('deleteTeam')
            ->with(new Uuid('01957109-b207-7488-a995-24af7d598730'));

        $this->client->request('DELETE', '/api/v1/team/01957109-b207-7488-a995-24af7d598730');

        $response = $this->client->getResponse();

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testRelocateTeam()
    {
        $teamData = file_get_contents(__DIR__ . '/../../../DataFixtures/relocated_team_response.json');

        $this->teamServiceMock->expects($this->once())
            ->method('relocate')
            ->with(new Uuid('01957109-b207-7488-a995-24af7d598730'), 'Bilbao')
            ->willReturn($this->makeTeam('Bilbao'));

        $this->client->request('POST', '/api/v1/team/01957109-b207-7488-a995-24af7d598730/relocate', [], [], [
            'CONTENT_TYPE' => 'application/json',
        ], json_encode([
            "city" => "Bilbao",
        ]));

        $response = $this->client->getResponse();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(json_decode($teamData, true), json_decode($response->getContent(), true));
    }

    private function makeTeam(string $relocateCity = null): Team
    {
        $teamData = file_get_contents(__DIR__ . '/../../../DataFixtures/team_response.json');
        $teamData = json_decode($teamData, true);

        $teamPlayer = new TeamPlayer(
            new Uuid($teamData['players'][0]['playerId']),
            $teamData['players'][0]['firstName'],
            $teamData['players'][0]['lastName'],
        );

        $team = new Team(
            new Uuid($teamData['teamId']),
            $teamData['name'],
            $teamData['yearFounded'],
            $teamData['stadium'],
            $relocateCity ?? $teamData['city'],
        );
        $team->addPlayer($teamPlayer);

        return $team;
    }

    private function makeTeamDTO(): TeamDTO
    {
        $teamData = file_get_contents(__DIR__ . '/../../../DataFixtures/team_response.json');
        $teamData = json_decode($teamData, true);

        return new TeamDTO(
            $teamData['name'],
            $teamData['yearFounded'],
            $teamData['stadium'],
            $teamData['city'],
        );
    }
}