<?php

namespace App\Resolver;

use ApiPlatform\GraphQl\Resolver\MutationResolverInterface;
use App\Repository\PlayerTeamRepository;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class PlayerMutation implements MutationResolverInterface
{
    public function __construct(private TokenStorageInterface $tokenStorageInterface, private PlayerTeamRepository $playerTeamRepository)
    {
    }
    public function __invoke(?object $item, array $context): ?object
    {
        $pp = $this->tokenStorageInterface->getToken();
        $team = $pp->getUser()->getTeam();
        ['worth' => $worth] = $context['args']['input'];
        $playerTeam  = $this->playerTeamRepository->findOneBy(['player' => $item, 'team' => $team, 'isCurrentTeam' => true]);
        if ($playerTeam) {
            $playerTeam->setCost($worth);
        }
        return $item;
    }
}
