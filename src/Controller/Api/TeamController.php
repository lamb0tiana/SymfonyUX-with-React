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
        return $this->post($request, Team::class);
    }
}
