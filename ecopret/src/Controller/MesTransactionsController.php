<?php

namespace App\Controller;

use App\Entity\Compte;
use App\Entity\Litige;
use App\Entity\Prestataire;
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
        $prestataire = $entityManager->getRepository(Prestataire::class)->findOneBy(['noUtisateur' => $utilisateur]);

        $transactionsUtilisateur = array_reverse($entityManager->getRepository(Transaction::class)->findBy(['Client' => $utilisateur]));
        $transactionsPrestataire = array_reverse($entityManager->getRepository(Transaction::class)->findBy(['Prestataire' => $prestataire]));

        foreach($transactionsPrestataire as $transaction) {
            if (!array_search($transaction, $transactionsUtilisateur)) {
                $transactionsUtilisateur[] = $transaction;
            }
        }

        $nbNotif = 0;
        $notifications = $this->getUser()->getNotifications();

        foreach ($notifications as $notification) {
            if ($notification->getStatus() == 0) {
                $nbNotif ++;
            }
        }

        if ($request->request->has('noter')) {
            // Rediriger vers la page Calendar avec l'identifiant de l'annonce
            return $this->redirectToRoute('noter', ['idTransaction' => $transaction->getId()]);
        }

        $transactions = $transactionsUtilisateur;

        $user = $entityManager->getRepository(Utilisateur::class)->findOneBy(['noCompte' => $this->getUser()->getId()]);
        return $this->render('mes_transactions/index.html.twig', [
            'controller_name' => 'MesTransactionsController',
            'transactions' => $transactions,
            'user' => $this->getUser(),
            'florins' => $user->getNbFlorains(),
            'nbNotif' => $nbNotif,
            'transactions' => $transactions
        ]);
    }
}
