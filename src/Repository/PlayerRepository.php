<?php

namespace App\Repository;

use App\Entity\Player;
use App\Entity\PlayerTeam;
use App\Entity\Team;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * @extends ServiceEntityRepository<Player>
 *
 * @method Player|null find($id, $lockMode = null, $lockVersion = null)
 * @method Player|null findOneBy(array $criteria, array $orderBy = null)
 * @method Player[]    findAll()
 * @method Player[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PlayerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, private RequestStack $requestStack)
    {
        parent::__construct($registry, Player::class);
    }

    public function save(Player $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Player $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }


    public function queryPlayer(): array
    {
        $conn = $this->getEntityManager()->getConnection();
        $playerTable = $this->_em->getClassMetadata($this->_entityName)->getTableName();
        $teamTable = $this->_em->getClassMetadata(Team::class)->getTableName();
        $playerTeamTable = $this->_em->getClassMetadata(PlayerTeam::class)->getTableName();
        $sql = sprintf('select
                    p.id,p.name ,p.surname ,
                    t.id ,t.name ,t.country_code ,t.money_balance
                from
                    %s pt
                inner join %s p on
                    pt.player_id = p.id
                INNER JOIN %s t on
                    pt.team_id = t.id', $playerTeamTable, $playerTable, $teamTable);
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery();
        return $resultSet->fetchAllAssociative();

    }
}
