<?php

namespace App\Validator;

use App\Entity\Team;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class TransfertPlayerValidator extends ConstraintValidator
{
    public function __construct(private EntityManagerInterface $manager, private RequestStack $request)
    {
    }
    public function validate($value, Constraint $constraint)
    {
        /** @var Team $team */
        $team = $this->request->getCurrentRequest()->attributes->get('team');
        if ($team->getMoneyBalance() < $value) {
            $message = sprintf('The team %s have no sufficient funds for this transfert, current funds [%d$]', $team->getName(), $team->getMoneyBalance());
            $this->context->buildViolation($message)
                ->addViolation();
        }
    }
}
