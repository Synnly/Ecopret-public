<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\UnsubscribeType;
use App\Entity\Utilisateur;
use App\Entity\Compte;


class AbonnementController extends AbstractController
{
    #[Route('/subscribe', name: 'app_subscribe')]
    public function subscribe(Request $request,EntityManagerInterface $entityManager): Response
    {
        if(!$this->getUser()){
            return $this->redirectToRoute('app_login');
        }
        $user = $this->getUser();
        $utilisateur = $entityManager->getRepository(Utilisateur::class)->findOneBy(['noCompte' => $entityManager->getRepository(Compte::class)->findOneBy(['id' => $user])]);

        if($utilisateur->isPaiement()){
            return $this->redirectToRoute('app_abonnement');
        }

        $nbNotif = 0;
        $notifications = $this->getUser()->getNotifications();
        
        foreach ($notifications as $notification) {
            if ($notification->getStatus() == 0) {
                $nbNotif ++;
            }
        }
        
        $user = $entityManager->getRepository(Utilisateur::class)->findOneBy(['noCompte' => $this->getUser()->getId()]);
        return $this->render('abonnement/subscribe.html.twig', [
            'controller_name' => 'AbonnementController',
            'user' => $this->getUser(),
            'florins' => $user->getNbFlorains(),
            'nbNotif' => $nbNotif,
        ]);
    }

    #[Route('/abonnement', name: 'app_abonnement')]
    public function abonnement(Request $request,EntityManagerInterface $entityManager): Response
    {
        if(!$this->getUser()){
            return $this->redirectToRoute('app_login');
        }
        $user = $this->getUser();
        $utilisateur = $entityManager->getRepository(Utilisateur::class)->findOneBy(['noCompte' => $entityManager->getRepository(Compte::class)->findOneBy(['id' => $user])]);

        if(!$utilisateur->isPaiement()){
            return $this->redirectToRoute('app_subscribe');
        }

        $nbNotif = 0;
        $notifications = $this->getUser()->getNotifications();
        
        foreach ($notifications as $notification) {
            if ($notification->getStatus() == 0) {
                $nbNotif ++;
            }
        }
        
        $form = $this->createForm(UnsubscribeType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $formData = $form->getData();

            $utilisateur->setPaiement(false);
            $utilisateur->setNbFlorains(0);
            $entityManager->persist($utilisateur);
            $entityManager->flush();

            return $this->redirectToRoute('app_main');
        }

        $user = $entityManager->getRepository(Utilisateur::class)->findOneBy(['noCompte' => $this->getUser()->getId()]);
        return $this->render('abonnement/abonnement.html.twig', [
            'controller_name' => 'AbonnementController',
            'form' => $form->createView(),
            'user' => $this->getUser(),
            'florins' => $user->getNbFlorains(),
            'nbNotif' => $nbNotif,
        ]);

    }
}
