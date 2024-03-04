<?php

namespace App\Controller;

use App\Entity\Compte;
use App\Entity\Emprunt;
use App\Entity\Service;
use App\Entity\Transaction;
use App\Form\FinServiceType;
use App\Form\RetourEmpruntType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class RetourEmpruntFinServiceController extends AbstractController
{
    #[Route('/retour/{transaction_id}', name: 'app_retour_emprunt')]
    public function retourEmprunt(int $transaction_id, Request $request, EntityManagerInterface $entityManager): Response
    {
        if(!$this->getUser()){
            return $this->redirectToRoute("app_page_accueil");
        }

        // Transaction inexistante
        if(!($transaction = $entityManager->getRepository(Transaction::class)->findOneBy(['id' => $transaction_id]))){
            return $this->redirectToRoute("app_page_accueil");
        }

        // Annonce pas un emprunt
        if(!($entityManager->getRepository(Emprunt::class)->findOneBy(['id_annonce' => $transaction->getAnnonce()]))){
            return $this->redirectToRoute("app_page_accueil");
        }

        $prestataire = $transaction->getAnnonce()->getPrestataire();
        $compte = $entityManager->getRepository(Compte::class)->findOneBy(['id' => $this->getUser()]);

        // User pas le prestataire de l'annonce
        if($prestataire->getNoUtisateur()->getNoCompte() != $compte){
            return $this->redirectToRoute("app_page_accueil");
        }

        // Transaction déja cloturée
        if($transaction->isEstCloture()){
            return $this->redirectToRoute("app_page_accueil");
        }

        $form = $this->createForm(RetourEmpruntType::class);
        $form->handleRequest($request);

        if($form->isSubmitted()){
            if($form['cloturer']->isClicked()){
                $transaction->setEstCloture(true);
                $entityManager->persist($transaction);
                $entityManager->flush();
                return $this->redirectToRoute("app_main");
            }
            else{
                return $this->redirectToRoute("app_decl_litige_transaction", ["transaction_id" => $transaction_id]);
            }
        }

        return $this->render('retour/emprunt.html.twig', [
            'controller_name' => 'RetourEmpruntFinServiceController',
            'RetourEmpruntForm' => $form->createView()
        ]);
    }

    #[Route('/fin/{transaction_id}', name: 'app_fin_service')]
    public function finService(int $transaction_id, Request $request, EntityManagerInterface $entityManager): Response
    {
        if(!$this->getUser()){
            return $this->redirectToRoute("app_page_accueil");
        }

        // Transaction inexistante
        if(!($transaction = $entityManager->getRepository(Transaction::class)->findOneBy(['id' => $transaction_id]))){
            return $this->redirectToRoute("app_page_accueil");
        }

        // Annonce pas un service
        if(!($entityManager->getRepository(Service::class)->findOneBy(['id_annonce' => $transaction->getAnnonce()]))){
            return $this->redirectToRoute("app_page_accueil");
        }

        $prestataire = $transaction->getAnnonce()->getPrestataire();
        $compte = $entityManager->getRepository(Compte::class)->findOneBy(['id' => $this->getUser()]);

        // User pas le prestataire de l'annonce
        if($transaction->getClient()->getNoCompte() != $compte){
            return $this->redirectToRoute("app_page_accueil");
        }

        // Transaction déja cloturée
        if($transaction->isEstCloture()){
            return $this->redirectToRoute("app_page_accueil");
        }

        $form = $this->createForm(FinServiceType::class);
        $form->handleRequest($request);

        if($form->isSubmitted()){
            if($form['cloturer']->isClicked()){
                $transaction->setEstCloture(true);
                $entityManager->persist($transaction);
                $entityManager->flush();
                return $this->redirectToRoute("app_main");
            }
            else{
                return $this->redirectToRoute("app_decl_litige_transaction", ["transaction_id" => $transaction_id]);
            }
        }

        return $this->render('retour/service.html.twig', [
            'controller_name' => 'RetourEmpruntFinServiceController',
            'FinServiceForm' => $form->createView()
        ]);
    }
}
