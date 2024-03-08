<?php

namespace App\Controller;

use App\Entity\Compte;
use App\Entity\Litige;
use App\Entity\Transaction;
use App\Entity\Utilisateur;
use App\Form\ListeLitigesType;
use App\Form\ListeTransactionsType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;

class MesTransactionsController extends AbstractController
{
    #[Route('/mes/transactions', name: 'app_mes_transactions')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $compte = $entityManager->getRepository(Compte::class)->findOneBy(['id'=>$this->getUser()]);
        $utilisateur = $entityManager->getRepository(Utilisateur::class)->findOneBy(['noCompte' => $compte->getId()]);

        $transactions = array_reverse($entityManager->getRepository(Transaction::class)->findBy(['Client' => $utilisateur]));

        $options = ['nbForms' => count($transactions)];
        foreach ($transactions as $transaction){
            $options[] = [
                'id_transaction' => $transaction->getId(),
                'nom_annonce' => $transaction->getAnnonce()->getNomAnnonce(),
                'statut' => ($transaction->isEstCloture() ? "Cloturée" : "En cours"),
            ];
        }

        $form = $this->createForm(ListeTransactionsType::class, ['data' => $options]);
        $form->handleRequest($request);
        return $this->render('mes_transactions/index.html.twig', [
            'controller_name' => 'MesTransactionsController',
            'TransactionType'=>$form->createView(),
        ]);
    }
}
