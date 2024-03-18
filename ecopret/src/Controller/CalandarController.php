<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\PlanningFormType;
use App\Entity\Annonce;
use App\Entity\Utilisateur;
use App\Entity\Compte;



class CalandarController extends AbstractController
{
    #[Route('/calandar', name: 'app_calandar')]
    public function index(Request $request, EntityManagerInterface $entityManager, $idAnnonce): Response
    {
        if(!$this->getUser()){
            return $this->redirectToRoute('app_login');
        }
        $user = $this->getUser();
        $utilisateur = $entityManager->getRepository(Utilisateur::class)->findOneBy(['noCompte' => $entityManager->getRepository(Compte::class)->findOneBy(['id' => $user])]);
        $annonce = $entityManager->getRepository(Annonce::class)->findOneBy(['id' => $idAnnonce]);
        $disponibilite = $annonce->getDisponibilite();
        $prestataire = $annonce->getPrestataire();

        if ($utilisateur->getId() != $prestataire->getNoUtisateur()->getId()){
            return $this->redirectToRoute('app_main');
        }
        
        global $erreur;


        $form = $this->createForm(PlanningFormType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            // Récupérer les données soumises
            $formData = $form->getData();

            $timeFrom = $request->request->get('event-time-from');
            $timeTo = $request->request->get('event-time-to');
            $infos = $request->request->get('infos');
        
            $annonce->setDisponibilite($infos);
            $entityManager->persist($annonce);
            $entityManager->flush();

            return $this->redirectToRoute('app_main');
        }

        return $this->render('calandar/index.html.twig', [
            'controller_name' => 'CalandarController',
            'disponibilite' => $disponibilite,
            'erreur' => $erreur,
            'form' => $form->createView(),
        ]);
    }
}
