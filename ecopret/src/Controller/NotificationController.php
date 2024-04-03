<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Compte;
use App\Entity\Utilisateur;
use App\Entity\Notification;
use App\Form\NotificationFormType;


class NotificationController extends AbstractController
{
    #[Route('/notification', name: 'app_notification')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $utilisateur = $entityManager->getRepository(Utilisateur::class)->findOneBy(['noCompte' => $entityManager->getRepository(Compte::class)->findOneBy(['id' => $user])]);

        
        $notifications = $user->getNotifications();
        
        $form = $this->createForm(NotificationFormType::class);
        
        $form->handleRequest($request);
        foreach ($notifications as $notification) {
            if ($notification->getStatus() != 2) {
                $notification->setStatus($notification->getStatus()+1);
                $entityManager->persist($notification);
            }
        }

        $entityManager->flush();
        

        return $this->render('notification/index.html.twig', [
            'notifications' => $notifications,
            'user' => $user,
            'florins' => $utilisateur->getNbFlorains(),
        ]);
    }
}
