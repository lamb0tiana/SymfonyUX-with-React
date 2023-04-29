<?php

namespace App\Controller\Api;

use App\Entity\Player;
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
}
