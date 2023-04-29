<?php

namespace App\EventSubscriber;

use App\Entity\PlayerTeam;
use App\Entity\Team;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;

class DoctrineSubscriber implements EventSubscriber
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    public function prePersist(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();
        if ($entity instanceof PlayerTeam) {
            $team = $entity->getTeam();
            $repository = $this->entityManager->getRepository(PlayerTeam::class);
            /** @var Team|null $currentOwner */
            $currentOwner = $repository->getTeamOfPlayer($entity->getPlayer());
            if ($currentOwner) {
                $team->setMoneyBalance($team->getMoneyBalance()- $entity->getCost());
                $currentOwner->setMoneyBalance($currentOwner->getMoneyBalance()+ $entity->getCost());
            }
        }
    }

    public function getSubscribedEvents(): array
    {
        return [
            Events::prePersist
        ];
    }
}
