<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PageDAccueilController extends AbstractController
{
    #[Route('/', name: 'app_page_accueil')]
    public function index(): Response
    {
        return $this->render('page_d_accueil/index.html.twig', [
            'title' => 'EcoPrêt',
        ]);
    }
}
