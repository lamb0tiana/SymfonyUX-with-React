<?php

namespace App\Controller\Api;

use App\Entity\TeamManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/manager')]
class ManagerController extends BaseApiController
{


    #[Route("/create", name: 'create_new_manager', methods: Request::METHOD_POST)]
    public function create(Request $request)
    {
       return $this->post($request, TeamManager::class);
    }
}
