<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class TeamController extends AbstractController
{
    #[Route('/create', name: 'create_team', methods: [Request::METHOD_POST])]
    public function create(Request $request): JsonResponse
    {
        return $this->json(['here']);
    }
}
