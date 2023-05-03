<?php

namespace App\Repository;

use App\Controller\Api\Constant;
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

    public function list(int $limit = Constant::PER_PAGE, int $offset = Constant::DEFAULT_PAGINATION_OFFSET): array
    {

        $teamTable = $this->_em->getClassMetadata($this->_entityName)->getTableName();
        $sql = sprintf('SELECT id, name, country_code isocode, money_balance funds from %s limit %d,%d', $teamTable, $offset, $limit);
        $data = $this->query($sql);

        $aggregationQuery = sprintf('SELECT count(*) totalRow from %s', $teamTable);
        $count = $this->query($aggregationQuery, false);
        return ['teams' => $data, 'count' => $count];
    }
}
