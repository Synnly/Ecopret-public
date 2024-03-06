<?php

namespace App\Controller;

use App\Entity\Annonce;
use App\Entity\Emprunt;
use App\Entity\Prestataire;
use App\Entity\Service;
use App\Entity\Utilisateur;
use App\Form\AjouterAnnonceType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MesAnnoncesController extends AbstractController
{
    #[Route('/mes_annonces', name: 'app_mes_annonces')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
        $user = $this->getUser();
        $form = $this->createForm(AjouterAnnonceType::class);
        $utilisateur = $entityManager->getRepository(Utilisateur::class)->findOneBy(['noCompte' => $this->getUser()->getId()]);
        $prestaire = $entityManager->getRepository(Prestataire::class)->findOneBy(['noUtisateur' => $utilisateur]);
        $annonces = $entityManager->getRepository(Annonce::class)->findBy(['prestataire' => $prestaire]);
        $typesAnnonces = [];
        foreach ($annonces as $annonce) {
            $emprunt = $entityManager->getRepository(Emprunt::class)->findOneBy(['id_annonce' => $annonce->getId()]);
            $service = $entityManager->getRepository(Service::class)->findOneBy(['id_annonce' => $annonce->getId()]);
        
            $typesAnnonces[] = ($emprunt !== null) ? 0 : (($service !== null) ? 1 : null);
        }
        return $this->render('mes_annonces/index.html.twig', [
            'controller_name' => 'MesAnnoncesController',
            'annonces' => $annonces,
            'form' => $form,
            'typesAnnonces' => $typesAnnonces,
        ]);
    }
}
