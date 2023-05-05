<?php

namespace App\Validator\Team;

use App\Entity\TeamManager;
use App\Repository\TeamManagerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class TeamValidator extends ConstraintValidator
{
    public function __construct(private Security $security, private TeamManagerRepository $managerRepository)
    {
    }
    public function validate($value, Constraint $constraint)
    {
        /** @var TeamManager $teamManager */
        $teamManager = $this->security->getUser();

        $team = $this->managerRepository->find($teamManager->getId())?->getTeam();
        if (null === $team) {
            return;
        }

        $message = sprintf('Sorry, the team [%s] is already set to your account.', $team->getName());
        $this->context->buildViolation($message)
            ->atPath('team')
            ->addViolation();
    }
}
