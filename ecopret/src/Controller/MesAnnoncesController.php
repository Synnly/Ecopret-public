<?php

namespace App\Controller;

use App\Entity\Annonce;
use App\Entity\Emprunt;
use App\Entity\Prestataire;
use App\Entity\Service;
use App\Entity\Utilisateur;
use Symfony\Component\HttpFoundation\Request;
use App\Form\ModifierAnnonceType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MesAnnoncesController extends AbstractController
{
    #[Route('/mes_annonces', name: 'app_mes_annonces')]
    public function index(EntityManagerInterface $entityManager, Request $request): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
        $user = $this->getUser();
        $form = $this->createForm(ModifierAnnonceType::class);
        $utilisateur = $entityManager->getRepository(Utilisateur::class)->findOneBy(['noCompte' => $this->getUser()->getId()]);
        $prestaire = $entityManager->getRepository(Prestataire::class)->findOneBy(['noUtisateur' => $utilisateur]);
        $annonces = $entityManager->getRepository(Annonce::class)->findBy(['prestataire' => $prestaire]);
        $typesAnnonces = [];
        foreach ($annonces as $annonce) {
            $emprunt = $entityManager->getRepository(Emprunt::class)->findOneBy(['id_annonce' => $annonce->getId()]);
            $service = $entityManager->getRepository(Service::class)->findOneBy(['id_annonce' => $annonce->getId()]);

            $typesAnnonces[] = ($emprunt !== null) ? 0 : (($service !== null) ? 1 : null);
        }
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $annonce = $entityManager->getRepository(Annonce::class)->findOneBy(['id' => $form->get("id")->getData()]);
            $annonce->setNomAnnonce($form->get("titre")->getData());
            $annonce->setDescription($form->get("description")->getData());
            $annonce->setPrix($form->get("prix")->getData());
            $es = $request->request->get('toggle');
            if ($es === "on") {
                if ($entityManager->getRepository(Service::class)->findOneBy(['id_annonce' => $annonce]) === null) {
                    $emprunt = $entityManager->getRepository(Emprunt::class)->findOneBy(['id_annonce' => $annonce]);
                    $entityManager->remove($emprunt);
                    $service = new Service();
                    $service->setIdAnnonce($annonce);
                    $entityManager->persist($service);
                }
            } elseif ($es === null) {
                if ($entityManager->getRepository(Emprunt::class)->findOneBy(['id_annonce' => $annonce]) === null) {
                    $service = $entityManager->getRepository(Service::class)->findOneBy(['id_annonce' => $annonce]);
                    $entityManager->remove($service);
                    $emprunt = new Emprunt();
                    $emprunt->setIdAnnonce($annonce);
                    $entityManager->persist($emprunt);
                }
            }
            $entityManager->persist($annonce);
            $entityManager->flush();
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
        return $this->render('mes_annonces/index.html.twig', [
            'controller_name' => 'MesAnnoncesController',
            'annonces' => $annonces,
            'form' => $form,
            'typesAnnonces' => $typesAnnonces,
        ]);
    }
}
