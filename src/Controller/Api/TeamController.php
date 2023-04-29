<?php

namespace App\Controller\Api;

use App\Entity\Team;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/teams')]
class TeamController extends BaseApiController
{
    #[Route('/create', name: 'create_team', methods: [Request::METHOD_POST])]
    public function create(Request $request): JsonResponse
    {
        return $this->post($request, Team::class);
    }

    #[Route('/player/add', name: 'add_player', methods: Request::METHOD_POST)]
    public function addPlayer(Request $request)
    {
        $aa = $this->serializer->deserialize($request->getContent(), Team::class, 'json');

        return $this->json($aa);
    }
}
