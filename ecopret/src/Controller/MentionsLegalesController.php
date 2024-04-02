<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Utilisateur;

class MentionsLegalesController extends AbstractController
{
    #[Route('/mentions_legales', name: 'app_mentions_legales')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $user = $entityManager->getRepository(Utilisateur::class)->findOneBy(['noCompte' => $this->getUser()->getId()]);

        return $this->render('mentions_legales/index.html.twig', [
            'controller_name' => 'MentionsLegalesController',
            'user' => $this->getUser(),
            'florins' => $user->getNbFlorains(),
        ]);
    }
}
