<?php

namespace App\Controller;

use App\Entity\Admin;
use App\Entity\Compte;
use App\Entity\Litige;
use App\Entity\Transaction;
use App\Form\DeclarerLitigeType;
use App\Form\ListeLitigesType;
use App\Form\VerifierLitigeType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class LitigeController extends AbstractController
{
    #[Route('/litige', name: 'app_litige')]
    public function index(Request $request,EntityManagerInterface $entityManager): Response
    {
        $compte = $entityManager->getRepository(Compte::class)->findOneBy(['id'=>$this->getUser()]);
        $litiges = array_reverse($entityManager->getRepository(Litige::class)->findBy(['plaignant' => $compte]));

        $options = ['nbForms' => count($litiges)];
        foreach ($litiges as $litige){
            $options[] = [
                'id_litige' => $litige->getId(),
                'nom_annonce' => $litige->getTransaction()->getAnnonce()->getNomAnnonce(),
                'nom_accuse' => $litige->getAccuse()->getNomCompte(),
                'description' => $litige->getDescription(),
                // Eventuellement changer les status en enumeration mais pour l'instant ca fait l'affaire
                'statut' => ($litige->getStatut() == 0 ? "Non traité" : ($litige->getStatut() == 1 ? "En cours de traitement" : "Traité")),
            ];
        }

        $form = $this->createForm(ListeLitigesType::class, ['data' => $options]);
        $form->handleRequest($request);

        return $this->render('litige/index.html.twig', [
            'controller_name' => 'LitigeController',
            'LitigeType' => $form->createView(),
        ]);
    }

    #[Route('/litige/declarer', name: 'app_decl_litige')]
    public function declarerLitige(Request $request,EntityManagerInterface $entityManager): Response
    {
        return $this->declarerLitigeTransaction($request, $entityManager);
    }

    #[Route('/litige/declarer/{transaction_id}', name: 'app_decl_litige_transaction')]
    public function declarerLitigeTransaction(Request $request,EntityManagerInterface $entityManager, int $transaction_id = null): Response
    {

        // TODO : Liste déroulante des transactions
        if(!$this->getUser()){
            $this->redirectToRoute("app_page_accueil");
        }

        $form = $this->createForm(DeclarerLitigeType::class);

        if($transaction_id != null){
            $form->get('transaction')->setData($transaction_id);
        }

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $erreur = null;

            // Transaction inexistante
            if(!($transaction = $entityManager->getRepository(Transaction::class)->findOneBy(['id' => $form['transaction']->getData()]))){
                $erreur = "La transaction n'existe pas.";
            }

            $compte = $entityManager->getRepository(Compte::class)->findOneBy(['id' => $this->getUser()]);

            // Le compte n'est pas en lien avec la transaction
            if($erreur == null && $transaction->getClient() != $compte && $transaction->getPrestataire() != $compte){
                $erreur = "Vous n'avez pas de lien avec la transaction.";
            }

            if($erreur != null){
                return $this->render('litige/declarer.html.twig', [
                    'controller_name' => 'LitigeController',
                    'DeclarerLitigeType' => $form->createView(),
                    'erreur' => $erreur,
                ]);
            }

            // Limite de litiges /annonce /compte atteinte (ici 3)
            if(count($entityManager->getRepository(Litige::class)->findBy(['plaignant' => $compte, 'transaction' => $transaction])) >= 3){
                return $this->render('litige/declarer.html.twig', [
                    'controller_name' => 'LitigeController',
                    'DeclarerLitigeType' => $form->createView(),
                    'erreur' => "Limite de litiges pour cette annonce atteinte. Vous ne pouvez plus déposer de litiges pour cette annonce."
                ]);
            }

            $litige = new Litige();
            $litige->setPlaignant($compte);

            // Si le client est celui qui se plaint, l'accusé est le prestataire et vice-versa
            $litige->setAccuse(($transaction->getClient()->getNoCompte() === $litige->getPlaignant()) ?
                $transaction->getPrestataire()->getNoUtisateur()->getNoCompte() :
                $transaction->getClient()->getNoCompte());

            $litige->setDescription($form['description']->getData());
            $litige->setStatut(0);
            $litige->setTransaction($transaction);

            $entityManager->persist($litige);
            $entityManager->flush();
            return $this->redirectToRoute("app_litige");
        }

        return $this->render('litige/declarer.html.twig', [
            'controller_name' => 'LitigeController',
            'DeclarerLitigeType' => $form->createView(),
        ]);
    }

    #[Route('/litige/verifier', name: 'app_litige_verifier')]
    public function verifierLitiges(Request $request,EntityManagerInterface $entityManager): Response
    {
        // User pas connecté
        if(!($user = $this->getUser())){
            return $this->redirectToRoute("app_page_accueil");
        }

        // User pas admin
        if(!($admin = $entityManager->getRepository(Admin::class)->findOneBy(['noCompte' => $entityManager->getRepository(Compte::class)->findOneBy(['id' => $user])]))){
            return $this->redirectToRoute("app_page_accueil");
        }

        // Recherche du litige qu'on traitait, sinon d'un litige pas traité
        if(!($litige = $entityManager->getRepository(Litige::class)->findOneBy(['statut' => 1, 'admin' => $admin]))){

            if(!($litige = $entityManager->getRepository(Litige::class)->findOneBy(['statut' => 0]))) {
                return $this->render('litige/aucunLitige.html.twig', [
                    'controller_name' => 'LitigeController',
                ]);
            }
            else{
                $litige->setAdmin($admin);
                $litige->setStatut(1);

                $entityManager->persist($litige);
                $entityManager->flush();
            }
        }

        $form = $this->createForm(VerifierLitigeType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $litige->setStatut(2);
            $litige->setEstValide($form['accepter']->isClicked());

            $entityManager->persist($litige);
            $entityManager->flush();

            return $this->redirectToRoute('app_litige_verifier');
        }

        return $this->render('litige/verifier.html.twig', [
            'controller_name' => 'LitigeController',
            'VerifierLitigeType' => $form->createView(),
            'typeUtil' => 'Client',
            'plaignant' => $litige->getPlaignant(),
            'transaction' => $litige->getTransaction(),
            'litige' => $litige,
            'lienContactAccuse' => '/',
            'lienContactPlaignant' => '/',
        ]);
    }
}
