<?php

namespace App\Repository;

use App\Entity\Player;
use App\Entity\PlayerTeam;
use App\Entity\Team;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PlayerTeam>
 *
 * @method PlayerTeam|null find($id, $lockMode = null, $lockVersion = null)
 * @method PlayerTeam|null findOneBy(array $criteria, array $orderBy = null)
 * @method PlayerTeam[]    findAll()
 * @method PlayerTeam[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PlayerTeamRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PlayerTeam::class);
    }

    public function save(PlayerTeam $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(PlayerTeam $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getCurrentPlayerTeam(Player $player): ?PlayerTeam
    {
        return $this->findOneBy(["player" => $player], ["id" => "desc"]);
    }

    public function getTeamOfPlayer(Player $player): ?Team
    {
        return $this->getCurrentPlayerTeam($player)?->getTeam();
    }
}
