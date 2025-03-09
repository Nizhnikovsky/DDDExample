<?php


namespace App\src\Infrastructure\Doctrine\Team\Repositoty;

use App\Domain\Team\Model\Team;
use App\Domain\Team\Model\TeamPlayer;
use App\Domain\Team\Repository\TeamRepository as TeamRepositoryInterface;
use App\Shared\Exception\TeamNotFoundException;
use App\Shared\Exception\ValueValidationException;
use App\Shared\ValueObjects\Uuid;
use App\src\Infrastructure\Doctrine\Player\Entity\Player;
use App\src\Infrastructure\Doctrine\Team\Entity\Team as TeamEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class TeamRepository extends ServiceEntityRepository implements TeamRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TeamEntity::class);
    }

    /**
     * @throws ValueValidationException
     * @throws TeamNotFoundException
     */
    public function getTeam(Uuid $teamId): Team
    {
        $qb = $this->getEntityManager()->createQueryBuilder()
            ->select('t, p')
            ->from(TeamEntity::class, 't')
            ->leftJoin('t.players', 'p')
            ->where('t.id = :id')
            ->setParameter('id', $teamId->value())
            ->getQuery();
        /** @var TeamEntity|null $result */
        $result = $qb->getOneOrNullResult();

        if (!$result) {
            throw new TeamNotFoundException($teamId);
        }

        $players = array_map(function (Player $player) {
            return new TeamPlayer(new Uuid($player->getId()), $player->getFirstName(), $player->getLastName());
        }, $result->getPlayers()->toArray());

        return new Team(
            new Uuid($result->getId()),
            $result->getName(),
            $result->getYearFounded(),
            $result->getStadium(),
            $result->getCity(),
            $players
        );
    }

    public function createTeam(Team $team): Team
    {
        $teamEntity = new TeamEntity();
        $teamEntity->setId(\Symfony\Component\Uid\Uuid::fromString($team->getTeamId()->value()));
        $teamEntity->setName($team->getName());
        $teamEntity->setStadium($team->getStadium());
        $teamEntity->setCity($team->getCity());
        $teamEntity->setYearFounded($team->getYearFounded());

        $this->getEntityManager()->persist($teamEntity);
        $this->getEntityManager()->flush();

        return $team;
    }

    public function updateTeam(Team $team): Team
    {
        /** @var TeamEntity $teamEntity */
        $teamEntity = $this->find(\Symfony\Component\Uid\Uuid::fromString($team->getTeamId()->value()));
        if (!$teamEntity) {
            throw new TeamNotFoundException($team->getTeamId());
        }

        $teamEntity->setCity($team->getCity());
        $this->getEntityManager()->persist($teamEntity);
        $this->getEntityManager()->flush();

        return $team;
    }

    public function deleteTeam(Uuid $teamId): void
    {
        $teamEntity = $this->find($teamId->value());
        if ($teamEntity) {
            $this->getEntityManager()->remove($teamEntity);
            $this->getEntityManager()->flush();
        }
    }
}