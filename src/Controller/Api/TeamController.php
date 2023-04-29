<?php

namespace App\Controller\Api;

use App\Entity\Team;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/team')]
class TeamController extends BaseApiController
{

    #[Route('/create', name: 'create_team', methods: [Request::METHOD_POST])]
    public function create(Request $request): JsonResponse
    {
        $content = $request->getContent();
        $team = $this->serializer->deserialize($content, Team::class, 'json');

        $errors = $this->getEntityErrors($team);
        if ($errors) {
            return $this->json($errors);
        }

        $this->manager->persist($team);
        $this->manager->flush();
        $response = $this->normalizer->normalize($team, 'json', ['groups' => ['read']]);

        return $this->json($response, Response::HTTP_CREATED);
    }
}
