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
        $erreur = "";
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

        $nbNotif = 0;
        $notifications = $this->getUser()->getNotifications();
        
        foreach ($notifications as $notification) {
            if ($notification->getStatus() == 0) {
                $nbNotif ++;
            }
        }
        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $annonce = $entityManager->getRepository(Annonce::class)->findOneBy(['id' => $form->get("id")->getData()]);
            $annonce->setNomAnnonce($form->get("titre")->getData());
            $des = htmlspecialchars($form->get("description")->getData(), ENT_QUOTES, 'UTF-8');
            $annonce->setDescription($des);
            $annonce->setPrix($form->get("prix")->getData());
            $linkpic = explode("|",$annonce->getImageAnnonce());
            $linkImagesForAnnouncement = "";
            $files = [$form->get('ajouterPhoto')->getData(), $form->get('ajouterPhoto2')->getData(), $form->get('ajouterPhoto3')->getData()];
            $i = 0;
            foreach($files as $file){
                if($file !== null){
                    $filename = md5(uniqid()).".png";
                    $linkImagesForAnnouncement = $linkImagesForAnnouncement.$filename.'|';
                    $file->move($this->getParameter('imgs_annonces'), $filename);
                }else {
                    if($i < count($linkpic)){
                        $linkImagesForAnnouncement = $linkImagesForAnnouncement.$linkpic[$i].'|';
                    }
                }
                $i++;
            }
            $annonce->setCategorie($form->get("categorie")->getData());
            $annonce->setImageAnnonce($linkImagesForAnnouncement);
            $es = $request->request->get('toggle');
            if ($es === null) {
                if ($entityManager->getRepository(Service::class)->findOneBy(['id_annonce' => $annonce]) === null) {
                    $emprunt = $entityManager->getRepository(Emprunt::class)->findOneBy(['id_annonce' => $annonce]);
                    $entityManager->remove($emprunt);
                    $service = new Service();
                    $service->setIdAnnonce($annonce);
                    $entityManager->persist($service);
                }
            } elseif ($es === "on") {
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
            $user = $entityManager->getRepository(Utilisateur::class)->findOneBy(['noCompte' => $this->getUser()->getId()]);
            return $this->render('mes_annonces/index.html.twig', [
                'controller_name' => 'MesAnnoncesController',
                'annonces' => $annonces,
                'form' => $form,
                'typesAnnonces' => $typesAnnonces,
                'user' => $this->getUser(),
                'florins' => $user->getNbFlorains(),
                'nbNotif' => $nbNotif,
            ]);
        }else if ($form->isSubmitted() && !$form->isValid()){
            $erreur = "pasValide";
        }
        $user = $entityManager->getRepository(Utilisateur::class)->findOneBy(['noCompte' => $this->getUser()->getId()]);
        
        return $this->render('mes_annonces/index.html.twig', [
            'controller_name' => 'MesAnnoncesController',
            'annonces' => $annonces,
            'form' => $form,
            'typesAnnonces' => $typesAnnonces,
            'error' => $erreur,
            'user' => $this->getUser(),
            'florins' => $user->getNbFlorains(),
            'nbNotif' => $nbNotif,
        ]);
    }
}
