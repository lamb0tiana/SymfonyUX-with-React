<?php

namespace App\Repository;

use App\Entity\Player;
use App\Entity\PlayerTeam;
use App\Entity\Team;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\AbstractQuery;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\RequestStack;

use function Doctrine\ORM\QueryBuilder;

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

    public function getPlayers(bool $forCurrentTeam = null, array $playersId = [])
    {
        if (!$playersId) {
            return [];
        }
        $qb = $this->createQueryBuilder('p');
        $qb->innerJoin("p.playerTeams", "playerTeams")->where($qb->expr()->in('p.id', $playersId))
        ->distinct();

        if ($forCurrentTeam !== null) {
            $qb
                ->andWhere($qb->expr()->eq("playerTeams.isCurrentTeam", ':currentStateValue'))
            ->setParameter('currentStateValue', $forCurrentTeam);
        }
        return $qb->getQuery()->getArrayResult();
    }
}
