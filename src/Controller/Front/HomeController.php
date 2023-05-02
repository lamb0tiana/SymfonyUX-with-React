<?php

namespace App\Controller\Front;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'index')]
    #[Route('/dashboard', name: 'dashboard')]
    public function index()
    {
        return $this->render('app.html.twig');
    }
}
