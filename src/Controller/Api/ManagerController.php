<?php

namespace App\Controller\Api;

use App\Entity\TeamManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/manager')]
class ManagerController extends BaseApiController
{
    #[Route("/create", name: 'create_new_manager', methods: Request::METHOD_POST)]
    public function create(Request $request)
    {
        $response =  $this->post($request, TeamManager::class);
        if ($response->getStatusCode() === Response::HTTP_BAD_REQUEST) {
            $data = ['errors' => array_map(fn ($e) => $e['message'], json_decode($response->getContent(), true))];
            return $this->json($data, $response->getStatusCode());
        }
        return $response;
    }
}
