<?php

namespace App\Security\Voter;

use App\Controller\Api\Traits\CurrentUserTrait;
use App\Entity\Player;
use App\Entity\PlayerTeam;
use App\Entity\Team;
use App\Entity\TeamManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class PlayerWorthVoter extends Voter
{
    public const CAN_SET_WORTH = 'can_set_worth';

    public function __construct(private EntityManagerInterface $entityManager)
    {
    }



    protected function supports(string $attribute, mixed $subject): bool
    {
        return $attribute === self::CAN_SET_WORTH && $subject instanceof Player;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $managerRepository = $this->entityManager->getRepository(TeamManager::class);
        $playerTeamRepository = $this->entityManager->getRepository(PlayerTeam::class);
        $user = $token->getUser();
        /** @var Team $playerTeam */
        $playerTeam = $playerTeamRepository->getTeamOfPlayer($subject);
        /** @var TeamManager $currentUser */
        $currentUser = $managerRepository->find($user->getId());
        return $currentUser && $playerTeam->getId() === $currentUser->getTeam()->getId();
    }
}
