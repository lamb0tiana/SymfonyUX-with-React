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
        $post = $request->getContent();
        try {
            $entity = $this->serializer->deserialize($post, $type, 'json');

            $errors = $this->getEntityErrors($entity);
            if ($errors) {
                return $this->json($errors, Response::HTTP_BAD_REQUEST);
            }

            $this->manager->persist($entity);
            $this->manager->flush();

            $response = $this->normalizer->normalize($entity, 'json', ['groups' => ['read']]);

            return $this->json($response, Response::HTTP_CREATED);
        } catch (\Exception $e) {
            $data = json_decode($post, true);
            ['money_balance' => $balance] = $data;
            if (preg_match("/[^(\d\s)]/", $balance)) {
                $message = [['field' =>'money_balance', 'message' => 'Not a number balance given']];
                return $this->json($message, Response::HTTP_BAD_REQUEST);
            }
        }
    }
}
