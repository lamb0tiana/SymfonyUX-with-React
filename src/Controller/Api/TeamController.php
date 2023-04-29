<?php

namespace App\Controller\Api;

use App\Controller\Api\Traits\EntityValidationTrait;
use App\Entity\Team;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/team')]
class TeamController extends AbstractController
{
    use EntityValidationTrait;

    public function __construct(private SerializerInterface $serializer, private NormalizerInterface $normalizer, private ValidatorInterface $validator)
    {
    }

    #[Route('/create', name: 'create_team', methods: [Request::METHOD_POST])]
    public function create(Request $request, EntityManagerInterface $manager): JsonResponse
    {
        $content = $request->getContent();
        $team = $this->serializer->deserialize($content, Team::class, 'json');

        $errors = $this->getEntityErrors($team);
        if ($errors) {
            return $this->json($errors);
        }

        $manager->persist($team);
        $manager->flush();
        $response = $this->normalizer->normalize($team, 'json', ['groups' => ['read']]);

        return $this->json($response, Response::HTTP_CREATED);
    }
}
