<?php

namespace App\Controller;

use App\Entity\Compte;
use App\Entity\Litige;
use App\Entity\Transaction;
use App\Form\DeclarerLitigeType;
use App\Form\ListeLitigesType;
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
        if(!$this->getUser()){
            $this->redirectToRoute("app_page_accueil");
        }

        $form = $this->createForm(DeclarerLitigeType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $litige = new Litige();
            $litige->setPlaignant($entityManager->getRepository(Compte::class)->findOneBy(['id' => $this->getUser()]));
            $transaction = $entityManager->getRepository(Transaction::class)->findOneBy(['id' => $form['transaction']->getData()]);

            // Si le client est celui qui se plaint, l'accusé est le prestataire et vice-versa
            $litige->setAccuse(($transaction->getClient()->getNoCompte() === $litige->getPlaignant()) ?
                $transaction->getPrestataire()->getNoUtisateur()->getNoCompte() :
                $transaction->getClient()->getNoCompte());

            $litige->setDescription($form['description']->getData());
            $litige->setStatut(0);
            $litige->setTransaction($transaction);

            $entityManager->persist($litige);
            $entityManager->flush();

            print "redirecting";
            $this->redirectToRoute("app_litige");
        }

        return $this->render('litige/declarer.html.twig', [
            'controller_name' => 'LitigeController',
            'DeclarerLitigeType' => $form->createView(),
        ]);
    }
}
