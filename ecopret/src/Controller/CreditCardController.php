<?php

namespace App\Controller;

use App\Entity\CarteCredit;
use App\Entity\Compte;
use App\Entity\Utilisateur;
use App\Form\CreditCardFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use DateTime;

class CreditCardController extends AbstractController
{
    #[Route('/payment_information', name: 'app_credit_card')]
    public function payment_information(Request $request, EntityManagerInterface $entityManager): Response
    {
        if(!$this->getUser()){
            return $this->redirectToRoute('app_login');
        }
        $user = $this->getUser();
        $erreur = '';

        
        // Récupération de la carte de credit
        if ($user->getCarteCredit() !== null) {
            $carte = $user->getCarteCredit();
        } else {
            return $this->redirectToRoute('infos_modif');
        }
        
        $carte = $user->getCarteCredit();

        //Création du formulaire
        $form = $this->createForm(CreditCardFormType::class, $carte);

        //Submit du formulaire
        $form->handleRequest($request);

        
        //Si c'est validé et conforme, j'envoie les données dans la table
        if ($form->isSubmitted() && $form->isValid()) {
            if (confirm('Merci d\'avoir souscrit un abonnement sur ECOPRET !')) {
                // Si l'utilisateur clique sur "OK", effectuer une action
                //window.location.href = "{{ path('main') }}"; // Redirection vers une action Symfony
            }
        

            $entityManager->persist($carte);
            $entityManager->flush();

            //Redirection vers la page main
            return $this->redirectToRoute('main');
        }

        return $this->render('credit_card/index.html.twig', [
            'creditCardForm' => $form->createView(),
            'erreur' => $erreur,
        ]);
    }
}
