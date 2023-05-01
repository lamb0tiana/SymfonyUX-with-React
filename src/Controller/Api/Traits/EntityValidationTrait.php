<?php

namespace App\Controller\Api\Traits;

use App\Entity\TraceableErrors;

use Symfony\Component\Validator\ConstraintViolationListInterface;

trait EntityValidationTrait
{

    public function getEntityErrors(TraceableErrors $entity): array
    {
        /** @var ConstraintViolationListInterface $errors */
        $errors = $this->validator->validate($entity);
        $errorsCollections = [];
        for ($e = 0; $e < $errors->count(); ++$e) {
            $currentError = $errors->get($e);
            array_push($errorsCollections, ['field' => $currentError->getPropertyPath(), 'message' => $currentError->getMessage()]);
        }

        return $errorsCollections;
    }
}
