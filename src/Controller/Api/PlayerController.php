<?php

namespace App\Controller\Api;

use App\Entity\Player;
use App\Repository\PlayerRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/players')]
class PlayerController extends BaseApiController
{
    #[Route('/create')]
    public function create(Request $request): JsonResponse
    {
        return $this->post($request, Player::class);
    }

    #[Route('/')]
    public function list(Request $request, PlayerRepository $repository): JsonResponse
    {
        return $this->json(['data' => $repository->queryPlayer(), 'meta' => ['totalRowCount'=> 10]]);
    }
}
