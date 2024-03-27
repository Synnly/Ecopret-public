<?php

namespace App\Controller;

use App\Entity\Admin;
use App\Entity\Compte;
use App\Entity\Transaction;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Annonce;
use App\Entity\Emprunt;
use App\Entity\Prestataire;
use App\Entity\Service;
use App\Entity\Utilisateur;
use App\Form\AjouterAnnonceType;
use App\Form\ChoisirAnnonceFormType;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/main', name: 'app_main')]
    public function index(EntityManagerInterface $entityManager): Response
    {

        if(!($user=$this->getUser())){
            return $this->redirectToRoute('app_login');
        }
        $html = '';
        if($entityManager->getRepository(Admin::class)->findOneBy(['noCompte' => $entityManager->getRepository(Compte::class)->findOneBy(['id' => $user])])){
            $html = "<li><a href=\"/litige/verifier\"><h4>Verifier litige</h4></a></li>";
        }
        //Page Main (il me fallait une redirection)
        //Si vous changer la route ou de fichier oublie pas de remplacer RegistrationController.php ligne 46
        return $this->render('main/index.html.twig', [
            'title' => 'EcoPrêt',
            'user' => $this->getUser(),
            'adminHtml' => $html,
            'form' => $form,
            'florins' => $user->getNbFlorains(),
            'annonces' => $annonces,
            'bool_prix' => $bool_prix
        ]);
    }

    #[Route('/main/choisir/{annonceId}', name: 'app_choisir')]
    public function choisirAnnonce(Request $request, EntityManagerInterface $entityManager): Response
    {
        if(!$this->getUser()){
            return $this->redirectToRoute('app_login');
        }

        #$utilisateur = $entityManager->getRepository(Utilisateur::class)->findOneBy(['id' => $this->getUser()]);

        #Je récupère et stock l'id de l'annonce sur laquelle on clique
        $uri = $request->getUri();
        $paths = explode('/', $uri);
        $idAnnonce = $paths[sizeof($paths) - 1];
        $annonceCliquee = $entityManager->getRepository(Annonce::class)->findOneBy(['id' => $idAnnonce]);

        $utilisateur = $entityManager->getRepository(Utilisateur::class)->findOneBy(['noCompte' => $this->getUser()->getId()]);
        $bool_prix = true;
        if($utilisateur->getNbFlorains() < intval($annonceCliquee->getPrix())) {
            $bool_prix = false;
        } else {
            $bool_prix = true;
        }

        $no_dispo = false;
        if($annonceCliquee->getDisponibilite() == "") {
            $no_dispo = true;
        }

        $form = $this->createForm(ChoisirAnnonceFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('oui')->isClicked()) {
                // L'utilisateur a confirmé le choix de l'annonce
                $user = $this->getUser();

                $disponibilites = $annonceCliquee->getDisponibiliteLisible();
                $indexChoice = $form->get('numero_choix')->getData() - 1;

                $transaction = new Transaction();
                $transaction->setAnnonce($annonceCliquee);
                $transaction->setPrestataire($annonceCliquee->getPrestataire());
                $transaction->setClient($entityManager->getRepository(Utilisateur::class)->findOneBy(['noCompte' => $user]));
                $transaction->setEstCloture(false);
                $entityManager->persist($transaction);

                if(!$annonceCliquee->getEstUnEmprunt()) {
                    $emprunt = $entityManager->getRepository(Emprunt::class)->findOneBy(['id_annonce' => $annonceCliquee->getId()]);
                    $emprunt->setIdEmprunteur($user->getId());
                    $emprunt->setDatesEmprunt($disponibilites[$indexChoice]);
                    $annonceCliquee->removeChoice($indexChoice);
                    $utilisateur->setNbFlorains($utilisateur->getNbFlorains() - $annonceCliquee->getPrix());
                    $entityManager->flush();
                } else {
                    $service = $entityManager->getRepository(Service::class)->findOneBy(['id_annonce' => $annonceCliquee->getId()]);
                    $service->setIdClient($user->getId());
                    $service->setDatesService($disponibilites[$indexChoice]);
                    $annonceCliquee->removeChoice($indexChoice);
                    $utilisateur->setNbFlorains($utilisateur->getNbFlorains() - $annonceCliquee->getPrix());
                    $entityManager->flush();
                }
                $entityManager->flush();

                return $this->redirectToRoute('app_main');

            } elseif ($form->get('non')->isClicked()) {
                // L'utilisateur a annulé le choix
                return $this->redirectToRoute('app_main');
            }
        }

        $annonces = $entityManager->getRepository(Annonce::class)->findAll();

        #dd($annonceCliquee->getDatesAnnonce());

        return $this->render('choisir/choisir.html.twig', [
            'title' => 'EcoPrêt',
            'user' => $this->getUser(),
            'choisirAnnonce' => $form->createView(),
            'annonces' => $annonces,
            'annonceCliquee' => $annonceCliquee,
            'bool_prix' => $bool_prix,
            'no_dispo' => $no_dispo,
            'listeDisponibilite' => $annonceCliquee->getDisponibiliteLisible()
        ]);
    }
}