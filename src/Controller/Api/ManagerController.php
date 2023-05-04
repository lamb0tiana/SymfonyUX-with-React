<?php

namespace App\Controller\Api;

use App\Entity\TeamManager;
use App\Repository\TeamManagerRepository;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/manager')]
class ManagerController extends BaseApiController
{
    #[Route("/create", name: 'create_new_manager', methods: Request::METHOD_POST)]
    public function create(Request $request, JWTTokenManagerInterface $encoder, TeamManagerRepository $repository)
    {
        $response =  $this->post($request, TeamManager::class);
        $responseContent = json_decode($response->getContent(), true);
        if ($response->getStatusCode() === Response::HTTP_BAD_REQUEST) {
            $data = ['errors' => array_map(fn ($e) => $e['message'], $responseContent)];
            return $this->json($data, $response->getStatusCode());
        }
        $user = $repository->find($responseContent['id']);
        return $this->json(['token' => $encoder->create($user)]);
    }
}
