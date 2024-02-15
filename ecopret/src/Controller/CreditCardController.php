<?php

namespace App\Controller;

use App\Entity\CarteCredit;
use App\Form\CreditCardFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CreditCardController extends AbstractController
{
    #[Route('/payment_information', name: 'app_credit_card')]
    public function payment_information(Request $request, EntityManagerInterface $entityManager): Response
    {
        //Création d'un compte 
        $carte = new CarteCredit();
        $erreur = 'Test';
        //Création du formulaire
        $form = $this->createForm(CreditCardFormType::class, $carte);
        dump($request->request->all());
        //Submit du formulaire
        $form->handleRequest($request);
        // Récupération de la valeur du champ date_expiration
        $dateExpiration = $request->request->get('date_expiration');
        // Définir la date d'expiration dans l'entité CarteCredit
        $carte->setDateExpiration(new \DateTime($dateExpiration));
            
        //Si c'est validé et conforme, je hash le mdp, j'envoie les données dans la table
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($carte);
            $entityManager->flush();

            //Redirection vers la page main
            return $this->redirectToRoute('main');
        }

        return $this->render('credit_card/index.html.twig', [
            'creditCardForm' => $form->createView(),
            'erreur' => $erreur,
        ]);
    }
}
