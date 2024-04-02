<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Transaction;
use App\Entity\Utilisateur;
use App\Entity\Compte;
use App\Entity\Annonce;
use App\Entity\Note;
use App\Entity\Prestataire;
use App\Form\NoterTransactionType;


class NoterTransactionController extends AbstractController
{
    #[Route('/noter/transaction', name: 'app_noter_transaction')]
    public function index(Request $request, EntityManagerInterface $entityManager, $idTransaction): Response
    {
        if(!$this->getUser()){
            return $this->redirectToRoute('app_login');
        }
        $user = $this->getUser();
        $utilisateur = $entityManager->getRepository(Utilisateur::class)->findOneBy(['noCompte' => $entityManager->getRepository(Compte::class)->findOneBy(['id' => $user])]);
        $transaction = $entityManager->getRepository(Transaction::class)->findOneBy(['id' => $idTransaction]);
        $prestataire = $transaction->getPrestataire();
        $client = $transaction->getClient();
        $annonce = $transaction->getAnnonce();
        $idComptePrestataire = $prestataire->getNoUtisateur()->getNoCompte();
        $comptePrestataire = $entityManager->getRepository(Compte::class)->findOneBy(['id' => $idComptePrestataire]);


        $form = $this->createForm(NoterTransactionType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $formData = $form->getData();
            
            $ratingAccount = $request->request->get('infos');
            $noteAccount = new Note();
            $noteAccount->setNote(intval($ratingAccount));

            $entityManager->persist($noteAccount);
            
            $comptePrestataire->addNote($noteAccount);

            $transaction->setEstNote(true);
           
            $entityManager->persist($comptePrestataire);
            $entityManager->persist($transaction);
            $entityManager->flush();


            return $this->redirectToRoute('app_main');
        }




        $user = $entityManager->getRepository(Utilisateur::class)->findOneBy(['noCompte' => $this->getUser()->getId()]);

        return $this->render('noter_transaction/index.html.twig', [
            'controller_name' => 'NoterTransactionController',
            'idTransaction' => $idTransaction,
            'annonce' => $annonce,
            'comptePrestataire' => $comptePrestataire,
            'form' => $form->createView(),
            'user' => $this->getUser(),
            'florins' => $user->getNbFlorains(),

        ]);
    }
}
