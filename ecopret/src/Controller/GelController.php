<?php

namespace App\Controller;

use App\Entity\Compte;
use App\Entity\Utilisateur;
use App\Form\GelCompteFormType;
use App\Form\SupprimerCompteFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class GelController extends AbstractController
{
    #[Route('/gel', name: 'app_gel')]
    public function gel(Request $request,EntityManagerInterface $entityManager): Response
    {
        // Si l'utilisateur n'est pas connecté on le redirige vers la page de connection
        if(!$this->getUser()){
            return $this->redirectToRoute('app_login');
        }
        $user = $this->getUser();
        $form = $this->createForm(GelCompteFormType::class);
        $form->handleRequest($request);

        //L'utilisateur à remplit les dates de gel du commpte
        if($form->isSubmitted() && $form->isValid()){
            $utilisateur = $entityManager->getRepository(Utilisateur::class)->findOneBy(['noCompte' => $entityManager->getRepository(Compte::class)->findOneBy(['id' => $user])]);

            $dateDebut = $form->get('deb')->getData();
            $dateFin = $form->get('fin')->getData();

            //Si la date de début est aujourd'hui on le met directement en gel
            if($dateDebut == new DateType('today UTC')){
                $utilisateur->setEstGele(true);
            }

            //Mise à jour des dates de gel
            $utilisateur->setDateDebGel($dateDebut);
            $utilisateur->setDateFinGel($dateFin);

            $entityManager->persist($user);
            $entityManager->flush();

        }

        return $this->render('gel/gel_compte.html.twig', [
            'controller_name' => 'GelController',
            'GelCompteFormType' => $form->createView(),
        ]);
    }
}
