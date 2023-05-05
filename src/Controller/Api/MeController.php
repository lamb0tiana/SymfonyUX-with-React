<?php

namespace App\Controller\Api;

use App\Entity\TeamManager;
use App\Repository\TeamManagerRepository;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/me')]
class MeController extends BaseApiController
{

    #[Route("/", name: 'me', methods: Request::METHOD_GET)]
    public function create(JWTTokenManagerInterface $encoder, TeamManagerRepository $repository)
    {
        $currentUser = $this->getUser();
        $user = $repository->find($currentUser->getId());
        return $this->json(['token' => $encoder->create($user)]);
    }
}
