<?php

namespace App\Controller\Api;

use App\Controller\Api\Traits\EntityValidationTrait;
use App\Entity\Team;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/team')]
class TeamController extends AbstractController
{
    use EntityValidationTrait;

    public function __construct(private SerializerInterface $serializer, private ValidatorInterface $validator)
    {
    }

    #[Route('/create', name: 'create_team', methods: [Request::METHOD_POST])]
    public function create(Request $request): JsonResponse
    {
        $content = $request->getContent();
        $team = $this->serializer->deserialize($content, Team::class, 'json');

        $errors = $this->getEntityErrors($team);
        if ($errors) {
            return $this->json($errors);
        }
        return $this->json($team, Response::HTTP_CREATED);
    }
}
