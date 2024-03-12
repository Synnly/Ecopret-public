<?php

namespace App\Controller;

use App\Entity\Annonce;
use App\Entity\Service;
use App\Form\SupprimerAnnonceFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

// Renommer la classe pour correspondre à celui dans origin/alpha
class AnnonceController extends AbstractController
{
    #[Route('/annonce/supprimer/{id}', name: 'app_annonce')]
    public function supprimerAnnonce(Request $request, EntityManagerInterface $entityManager, int $id = null): Response
    {
        if(!$this->getUser()){
            return $this->redirectToRoute('app_main');
        }

        if(!($annonce = $entityManager->getRepository(Annonce::class)->findOneBy(['id' => $id]))){
            return $this->redirectToRoute('app_mes_annonces');
        }

        if($annonce->getPrestataire()->getNoUtisateur()->getNoCompte() != $this->getUser()){
            return $this->redirectToRoute('app_mes_annonces');
        }

        $form = $this->createForm(SupprimerAnnonceFormType::class);
        $form->handleRequest($request);

        if($form->isSubmitted()){
            if($form['oui']->isClicked()){
                $entityManager->remove($annonce);
                $entityManager->flush();
            }

            return $this->redirectToRoute('app_mes_annonces');
        }

        return $this->render('annonce/supprimer.html.twig', [
            'controller_name' => 'AnnonceController',
            'SupprimerAnnonceFormType' => $form
        ]);
    }
}
