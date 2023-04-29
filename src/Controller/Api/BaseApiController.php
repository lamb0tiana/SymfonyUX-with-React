<?php

namespace App\Controller\Api;

use App\Controller\Api\Traits\EntityValidationTrait;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class BaseApiController extends AbstractController
{
    use EntityValidationTrait;

    public function __construct(
        protected SerializerInterface $serializer,
        protected NormalizerInterface $normalizer,
        protected ValidatorInterface $validator,
        protected EntityManagerInterface $manager
    ) {
    }
}
