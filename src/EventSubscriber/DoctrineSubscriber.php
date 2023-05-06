<?php

namespace App\EventSubscriber;

use App\Entity\Player;
use App\Entity\PlayerTeam;
use App\Entity\Team;
use App\Entity\TeamManager;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class DoctrineSubscriber implements EventSubscriber
{
    public function __construct(private EntityManagerInterface $entityManager, private UserPasswordHasherInterface $passwordHasher, private Security $user)
    {
    }

    private function getUserManager(): ?TeamManager
    {
        $repository = $this->entityManager->getRepository(TeamManager::class);
        $user = $this->user->getUser();
        /** @var TeamManager $teamManager */
        $teamManager = $repository->find($user->getId());
        return $teamManager;
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
        } elseif ($entity instanceof TeamManager) {
            $this->handlePassword($entity);
        } elseif ($entity instanceof Team) {
            $entity->setTeamManager($this->getUserManager());
        } elseif ($entity instanceof Player) {
            $playerTeam = new PlayerTeam();
            $playerTeam->setPlayer($entity)->setTeam($this->getUserManager()->getTeam());
            $this->entityManager->persist($playerTeam);
        }
    }


    private function handlePassword(TeamManager $teamManager)
    {
        $password = $this->passwordHasher->hashPassword($teamManager, $teamManager->getPassword());
        $teamManager->setPassword($password);
    }


    public function getSubscribedEvents(): array
    {
        return [
            Events::prePersist
        ];
    }
}
