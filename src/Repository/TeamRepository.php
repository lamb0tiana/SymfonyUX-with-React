<?php

namespace App\Repository;

use App\Controller\Api\Constant;
use App\Entity\Player;
use App\Entity\PlayerTeam;
use App\Entity\Team;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Team>
 *
 * @method Team|null find($id, $lockMode = null, $lockVersion = null)
 * @method Team|null findOneBy(array $criteria, array $orderBy = null)
 * @method Team[]    findAll()
 * @method Team[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TeamRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Team::class);
    }

    public function save(Team $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Team $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    private function query(string $sql, bool $isArray = true): array|int
    {
        $conn = $this->getEntityManager()->getConnection();
        $stmt = $conn->prepare($sql);
        $query = $stmt->executeQuery();
        return $isArray ? $query->fetchAllAssociative() : $query->fetchOne();
    }

    public function getList(int $limit = Constant::PER_PAGE, int $offset = Constant::DEFAULT_PAGINATION_PAGE_OFFSET): array
    {

        $teamTable = $this->_em->getClassMetadata($this->_entityName)->getTableName();
        $offset = ($offset -1) * $limit;
        $sql = sprintf('SELECT id, name, country_code isocode, money_balance funds, slug from %s limit %d,%d', $teamTable, $offset, $limit);
        $data = $this->query($sql);

        $aggregationQuery = sprintf('SELECT count(*) totalRow from %s', $teamTable);
        $count = $this->query($aggregationQuery, false);
        return ['teams' => $data, 'count' => $count];
    }

    public function getPlayerList(Team $team): array
    {
        $playerTeamTable = $this->_em->getClassMetadata(PlayerTeam::class)->getTableName();
        $playerTable = $this->_em->getClassMetadata(Player::class)->getTableName();

        $sql = sprintf('SELECT p.id, p.name, p.surname FROM %s p inner join %s pt on p.id = pt.player_id and pt.team_id = %d', $playerTable, $playerTeamTable, $team->getId());
        return $this->query($sql);
    }
}
