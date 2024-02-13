<?php

namespace App\Controller;

use App\Entity\Lieu;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class InsertLieuxController extends AbstractController
{
    #[Route('/insert/lieux', name: 'app_insert_lieux')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        include_once ("../public/villes.php");
        set_time_limit(0);  // Pas de limite de temps d'execution
        foreach($villes as $nomville){
            $lieu = new Lieu();
            $lieu->setNomLieu($nomville);
            $entityManager->persist($lieu);
            $entityManager->flush();
        }
        set_time_limit(30); // Remise de la limite de temps d'execution
        return $this->render('insert_lieux/index.html.twig', [
            'controller_name' => 'InsertLieuxController',
        ]);
    }
}
