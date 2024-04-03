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

        $nbNotif = 0;
        $notifications = $this->getUser()->getNotifications();
        
        foreach ($notifications as $notification) {
            if ($notification->getStatus() == 0) {
                $nbNotif ++;
            }
        }

        return $this->render('mentions_legales/index.html.twig', [
            'controller_name' => 'MentionsLegalesController',
            'user' => $this->getUser(),
            'florins' => $user->getNbFlorains(),
            'nbNotif' => $nbNotif,
        ]);
    }
}
