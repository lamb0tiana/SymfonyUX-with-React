<?php

namespace App\Validator;

use App\Entity\Player;
use App\Entity\PlayerTeam;
use App\Entity\Team;
use App\Entity\TeamManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class TransfertPlayerValidator extends ConstraintValidator
{
    public function __construct(private Security $security, private EntityManagerInterface $manager, private RequestStack $request)
    {
    }
    public function validate($value, Constraint $constraint)
    {
        /** @var TeamManager $currentUser */
        $currentUser = $this->security->getUser();

        $team = $currentUser->getTeam();
        /** @var Player $player */
        $player = $this->request->getCurrentRequest()->attributes->get('player');

        $owner = $this->manager->getRepository(PlayerTeam::class)->getTeamOfPlayer($player);
        if ($owner && $owner !== $team && $team->getMoneyBalance() < $value) {
            $message = sprintf('The team %s have no sufficient funds for this transfert, current funds [%d$]', $team->getName(), $team->getMoneyBalance());
            $this->context->buildViolation($message)
                ->addViolation();
        }
    }
}
