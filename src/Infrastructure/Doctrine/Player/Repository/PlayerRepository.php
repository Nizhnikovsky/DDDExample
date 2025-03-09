<?php

namespace App\Infrastructure\Doctrine\Player\Repository;

use App\Domain\Player\Model\Player;
use App\Domain\Player\Model\PlayerTeam;
use App\Domain\Player\Repository\PlayerRepository as PlayerRepositoryInterface;
use App\Domain\Player\ValueObject\AgeValue;
use App\Domain\Player\ValueObject\NumberValue;
use App\Domain\Player\ValueObject\PositionValue;
use App\Infrastructure\Doctrine\Player\Entity\Player as PlayerEntity;
use App\Infrastructure\Doctrine\Team\Entity\Team as TeamEntity;
use App\Shared\Exception\PlayerNotFoundException;
use App\Shared\Exception\TeamNotFoundException;
use App\Shared\Exception\ValueValidationException;
use App\Shared\ValueObjects\Uuid;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class PlayerRepository extends ServiceEntityRepository implements PlayerRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PlayerEntity::class);
    }

    /**
     * @throws ValueValidationException
     * @throws PlayerNotFoundException
     */
    public function getPlayer(Uuid $playerId): ?Player
    {
        $qb = $this->getEntityManager()->createQueryBuilder()
                ->select('p, t.id as teamId, t.name as teamName')
                ->from(PlayerEntity::class, 'p')
                ->join(TeamEntity::class, 't', 'WITH', 'p.team = t.id')
                ->where('p.id = :id')
                ->setParameter('id', $playerId->value())
                ->getQuery();
        $playerData = $qb->getOneOrNullResult();

        if (!$playerData) {
            throw new PlayerNotFoundException($playerId);
        }

        $playerTeam = new PlayerTeam(new Uuid($playerData['teamId']), $playerData['teamName']);
        /** @var PlayerEntity $player */
        $player = $playerData[0];

        return new Player(
            new Uuid($player->getId()),
            $player->getFirstName(),
            $player->getLastName(),
            new AgeValue($player->getAge()),
            new NumberValue($player->getPlayerNumber()),
            new PositionValue($player->getPosition()),
            $playerTeam,
            new \DateTimeImmutable($player->getJoinedAt()->format(DATE_W3C))
        );
    }

    /**
     * @throws TeamNotFoundException
     */
    public function createPlayer(Player $player): Player
    {
        $qb = $this->getEntityManager()->createQueryBuilder()
            ->select('t')
            ->from(TeamEntity::class, 't')
            ->where('t.id = :id')
            ->setParameter('id', $player->getTeam()->getTeamId()->value())
            ->getQuery();
        $team = $qb->getOneOrNullResult();

        if (!$team) {
            throw new TeamNotFoundException($player->getTeam()->getTeamId()->value());
        }

        $playerEntity = new PlayerEntity();
        $playerEntity->setId(\Symfony\Component\Uid\Uuid::fromString($player->getPlayerId()->value()));
        $playerEntity->setFirstName($player->getFirstName());
        $playerEntity->setLastName($player->getLastName());
        $playerEntity->setAge($player->getAge()->getValue());
        $playerEntity->setPlayerNumber($player->getPlayerNumber()->getValue());
        $playerEntity->setPosition($player->getPosition()->getPosition());
        $playerEntity->setJoinedAt(new \DateTimeImmutable());
        $playerEntity->setTeam($team);

        $this->getEntityManager()->persist($playerEntity);
        $this->getEntityManager()->flush();

        return $player;
    }

    public function updatePlayer(Player $player): Player
    {
        $playerEntity = $this->find(\Symfony\Component\Uid\Uuid::fromString($player->getPlayerId()->value()));
        $playerEntity->setFirstName($player->getFirstName());
        $playerEntity->setLastName($player->getLastName());
        $playerEntity->setAge($player->getAge()->getValue());
        $playerEntity->setPlayerNumber($player->getPlayerNumber()->getValue());
        $playerEntity->setPosition($player->getPosition()->getPosition());

        $this->getEntityManager()->persist($playerEntity);
        $this->getEntityManager()->flush();

        return $player;
    }

    public function deletePlayer(Uuid $playerId): void
    {
        $playerEntity = $this->find($playerId->value());
        if ($playerEntity) {
            $this->getEntityManager()->remove($playerEntity);
            $this->getEntityManager()->flush();
        }
    }

    public function getPlayersByTeamId(Uuid $teamId): array
    {
        return $this->createQueryBuilder('p')
            ->select('p.id, p.firstName, p.lastName')
            ->where('p.team = :teamId')
            ->setParameter('teamId', $teamId->value())
            ->getQuery()
            ->getArrayResult();
    }
}
