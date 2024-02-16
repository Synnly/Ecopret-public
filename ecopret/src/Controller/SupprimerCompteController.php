<?php

namespace App\Controller;

use App\Entity\Compte;
use App\Form\SupprimerCompteFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;

class SupprimerCompteController extends AbstractController
{
    #[Route('/supprimer/compte', name: 'app_supprimer_compte')]
    public function supprimerCompte(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Si l'utilisateur n'est pas connecté on le redirige vers la page de connection
        if(!$this->getUser()){
            return $this->redirectToRoute('app_login');
        }

        $form = $this->createForm(SupprimerCompteFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('valider')->isClicked()) {
                // L'utilisateur a confirmé la suppression du compte
                //Récupération de l'utilisateur courant
                $user = $entityManager->getRepository(Compte::class)->findOneBy(['id' => $this->getUser()]);

                $entityManager->remove($user);
                $entityManager->flush();
                return $this->redirectToRoute('app_main');



            } elseif ($form->get('annuler')->isClicked()) {
                // L'utilisateur a annulé la suppression du compte
                return $this->redirectToRoute('app_main');
            }
        }


        return $this->render('supprimer_compte/supprimer_compte.html.twig', [
            'controller_name' => 'SupprimerCompteController',
            'SupprimerCompteFormType' => $form->createView(),
        ]);
    }

}
