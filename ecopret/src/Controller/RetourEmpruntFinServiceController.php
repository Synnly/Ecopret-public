<?php

namespace App\Controller;

use App\Entity\Transaction;
use App\Form\RetourEmpruntType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class RetourEmpruntFinServiceController extends AbstractController
{
    #[Route('/retour/{transaction_id}', name: 'app_retour_emprunt_fin_service')]
    public function index(int $transaction_id, Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(RetourEmpruntType::class);
        $form->handleRequest($request);

        if($form->isSubmitted()){
            if($form['cloturer']->isClicked()){
                $transaction = $entityManager->getRepository(Transaction::class)->findOneBy(['id' => $transaction_id]);
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
}
