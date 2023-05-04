<?php

namespace App\Controller\Front;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'index')]
    #[Route('/login', name: 'login')]
    #[Route('/team/{id}', name: 'teamview')]
    public function index(Request $request)
    {
        return $this->render('app.html.twig');
    }
}
