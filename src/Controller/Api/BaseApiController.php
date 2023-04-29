<?php

namespace App\Controller\Api;

use App\Controller\Api\Traits\EntityValidationTrait;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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

    public function post(Request $request, string $type): JsonResponse
    {
        $entity = $this->serializer->deserialize($request->getContent(), $type, 'json');

        $errors = $this->getEntityErrors($entity);
        if ($errors) {
            return $this->json($errors);
        }

        $this->manager->persist($entity);
        $this->manager->flush();

        $response = $this->normalizer->normalize($entity, 'json', ['groups' => ['read']]);

        return $this->json($response, Response::HTTP_CREATED);
    }
}
