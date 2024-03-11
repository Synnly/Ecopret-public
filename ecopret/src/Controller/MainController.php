<?php

namespace App\Controller;

use App\Entity\Annonce;
use App\Entity\Compte;
use App\Entity\Emprunt;
use App\Entity\Prestataire;
use App\Entity\Service;
use App\Entity\Utilisateur;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\AjouterAnnonceType;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{

    #[Route('/main', name: 'app_main')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $erreur = "";
        if(!$this->getUser()){
            return $this->redirectToRoute('app_login');
        }
        $form = $this->createForm(AjouterAnnonceType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $linkImagesForAnnouncement = "";
            $files = [$form->get('ajouterPhoto')->getData(), $form->get('ajouterPhoto2')->getData(), $form->get('ajouterPhoto3')->getData()];
            foreach($files as $file){
                if($file !== null){
                    $filename = md5(uniqid()).".png";
                    $linkImagesForAnnouncement = $linkImagesForAnnouncement.$filename.'|';
                    $file->move($this->getParameter('imgs_annonces'), $filename);
                }
            }
            $annonce = new Annonce();
            $annonce->setNomAnnonce($form->get("titre")->getData());
            $des = nl2br(htmlspecialchars($form->get("description")->getData(), ENT_QUOTES, 'UTF-8'));
            $annonce->setDescription($des);
            $annonce->setPrix($form->get("prix")->getData());
            $annonce->setImageAnnonce($linkImagesForAnnouncement);
            $annonce->setEstRendu(false);
        
            $annonce->setEstEnLitige(false);
            $user = $entityManager->getRepository(Utilisateur::class)->findOneBy(['noCompte' => $this->getUser()->getId()]);
            $prestataire = $entityManager->getRepository(Prestataire::class)->findOneBy(['noUtisateur' => $user]);
            if($prestataire !== null){
                $prestataire->setNoUtisateur($user); 
            }else {
                $prestataire = new Prestataire();
                $prestataire->setNoUtisateur($user); 
            }
            $annonce->setPrestataire($prestataire);
            $annonce->setDisponibilite("");
            $es = $request->request->get('toggle');
            if($es === "on"){
                $service = new Service();
                $service->setIdAnnonce($annonce);
                $entityManager->persist($service);
            }elseif ($es === null){
                $emprunt = new Emprunt();
                $emprunt->setIdAnnonce($annonce);
                $entityManager->persist($emprunt);
            }
            $entityManager->persist($prestataire);
            $entityManager->persist($annonce);
            $entityManager->flush(); 
            $form = $this->createForm(AjouterAnnonceType::class); 
            if ($request->request->has('plannin-btn')) {
                // Rediriger vers la page Calendar avec l'identifiant de l'annonce
                return $this->redirectToRoute('event_add', ['idAnnonce' => $annonce->getId()]);
            }             
        }else if ($form->isSubmitted() && !$form->isValid()){
            $erreur = "pasValide";
        }
        return $this->render('main/index.html.twig', [
            'title' => 'EcoPrêt',
            'user' => $this->getUser(),
            'form' => $form,
            'error' => $erreur,
        ]);
    }
}