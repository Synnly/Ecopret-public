<?php

namespace App\Controller;

use App\Entity\Admin;
use App\Entity\Compte;
use App\Entity\Annonce;
use App\Entity\Emprunt;
use App\Entity\FileAttenteAnnonce;
use App\Entity\Transaction;
use App\Entity\Prestataire;
use App\Entity\Service;
use App\Entity\Utilisateur;
use App\Form\AjouterAnnonceType;
use App\Form\AjouterListeAttenteType;
use App\Form\ChoisirAnnonceFormType;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/main', name: 'app_main')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {

        if (!($user = $this->getUser())) {
            return $this->redirectToRoute('app_login');
        }
        $html = '';
        if ($entityManager->getRepository(Admin::class)->findOneBy(['noCompte' => $entityManager->getRepository(Compte::class)->findOneBy(['id' => $user])])) {
            $html = "<li><a href=\"/litige/verifier\"><h4>Verifier litige</h4></a></li>";
        }
        //Page Main (il me fallait une redirection)
        //Si vous changer la route ou de fichier oublie pas de remplacer RegistrationController.php ligne 46
        $form = $this->createForm(AjouterAnnonceType::class);
        $form->handleRequest($request);
        $user = $entityManager->getRepository(Utilisateur::class)->findOneBy(['noCompte' => $this->getUser()->getId()]);
        if ($form->isSubmitted() && $form->isValid()) {
            $linkImagesForAnnouncement = "";
            $files = [$form->get('ajouterPhoto')->getData(), $form->get('ajouterPhoto2')->getData(), $form->get('ajouterPhoto3')->getData()];
            foreach ($files as $file) {
                if ($file !== null) {
                    $filename = md5(uniqid()) . ".png";
                    $linkImagesForAnnouncement = $linkImagesForAnnouncement . $filename . '|';
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
            $annonce->setCategorie($form->get("categorie")->getData());

            $annonce->setEstEnLitige(false);

            $prestataire = $entityManager->getRepository(Prestataire::class)->findOneBy(['noUtisateur' => $user]);
            if ($prestataire !== null) {
                $prestataire->setNoUtisateur($user);
            } else {
                $prestataire = new Prestataire();
                $prestataire->setNoUtisateur($user);
            }
            $annonce->setPrestataire($prestataire);
            $annonce->setDisponibilite("");

            $es = $request->request->get('toggle');
            if ($es === null) {
                $annonce->setEstUnEmprunt(true);
                $service = new Service();
                $service->setIdAnnonce($annonce);
                $entityManager->persist($service);
            } elseif ($es === "on") {
                $annonce->setEstUnEmprunt(false);
                $emprunt = new Emprunt();
                $emprunt->setIdAnnonce($annonce);
                $entityManager->persist($emprunt);
            }
            $entityManager->persist($prestataire);
            $entityManager->persist($annonce);
            $entityManager->flush();
            $form = $this->createForm(AjouterAnnonceType::class);
            if ($request->request->has('now-btn')) {
                // Rediriger vers la page Calendar avec l'identifiant de l'annonce
                return $this->redirectToRoute('event_add', ['idAnnonce' => $annonce->getId()]);
            }
        } else if ($form->isSubmitted() && !$form->isValid()) {
            $erreur = "pasValide";
        }

        $annonces = $entityManager->getRepository(Annonce::class)->findAll();
        $bool_prix = null;

        return $this->render('main/index.html.twig', [
            'title' => 'EcoPrêt',
            'adminHtml' => $html,
            'form' => $form,
            'user' => $this->getUser(),
            'florins' => $user->getNbFlorains(),
            'annonces' => $annonces,
            'bool_prix' => $bool_prix
        ]);
    }

    #[Route('/main/choisir/{annonceId}', name: 'app_choisir')]
    public function choisirAnnonce(Request $request, EntityManagerInterface $entityManager): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        #$utilisateur = $entityManager->getRepository(Utilisateur::class)->findOneBy(['id' => $this->getUser()]);

        #Je récupère et stock l'id de l'annonce sur laquelle on clique
        $uri = $request->getUri();
        $paths = explode('/', $uri);
        $idAnnonce = $paths[sizeof($paths) - 1];
        $annonceCliquee = $entityManager->getRepository(Annonce::class)->findOneBy(['id' => $idAnnonce]);

        $utilisateur = $entityManager->getRepository(Utilisateur::class)->findOneBy(['noCompte' => $this->getUser()]);
        dump($idAnnonce, $utilisateur);
        $bool_prix = true;
        if ($utilisateur->getNbFlorains() < intval($annonceCliquee->getPrix())) {
            $bool_prix = false;
        } else {
            $bool_prix = true;
        }

        $no_dispo = false;
        if ($annonceCliquee->getDisponibilite() == "") {
            $no_dispo = true;
        }
        $formlist = $this->createForm(AjouterListeAttenteType::class);
        $formlist->handleRequest($request);
        $DejaAjouter = "";
        $DejaAjouter = $entityManager->getRepository(FileAttenteAnnonce::class)->findOneBy(["no_utilisateur" => $utilisateur, "no_annonce" => $idAnnonce]);
        if ($formlist->isSubmitted()) {
            if ($DejaAjouter === null) {
                $fileAttente = new FileAttenteAnnonce();
                $fileAttente->setNoUtilisateur($utilisateur);
                $fileAttente->setNoAnnonce($annonceCliquee);
                $entityManager->persist($fileAttente);
                $entityManager->flush();
            }
        }
        $DejaAjouter = $entityManager->getRepository(FileAttenteAnnonce::class)->findOneBy(["no_utilisateur" => $utilisateur, "no_annonce" => $idAnnonce]);

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
                $entityManager->flush();


                if (gettype($form->get('numero_choix')->getData()) != "int" || $form->get('numero_choix')->getData() - 1 < 0) {
                    return $this->redirectToRoute("app_main");
                } else {
                    $disponibilites = $annonceCliquee->getDisponibiliteLisible();
                    $indexChoice = $form->get('numero_choix')->getData() - 1;

                    if (!$annonceCliquee->getEstUnEmprunt()) {
                        $emprunt = $entityManager->getRepository(Emprunt::class)->findOneBy(['id_annonce' => $annonceCliquee->getId()]);
                        $emprunt->setIdEmprunteur($user->getId());
                        $emprunt->setDatesEmprunt($disponibilites[$indexChoice]);
                        $annonceCliquee->removeChoice($indexChoice);
                        $utilisateur->setNbFlorains($utilisateur->getNbFlorains() - $annonceCliquee->getPrix());
                        $entityManager->persist($utilisateur);
                        $entityManager->flush();
                    } else {
                        $service = $entityManager->getRepository(Service::class)->findOneBy(['id_annonce' => $annonceCliquee->getId()]);
                        $service->setIdClient($user->getId());
                        $service->setDatesService($disponibilites[$indexChoice]);
                        $annonceCliquee->removeChoice($indexChoice);
                        $utilisateur->setNbFlorains($utilisateur->getNbFlorains() - $annonceCliquee->getPrix());
                        $entityManager->persist($utilisateur);
                        $entityManager->flush();
                    }
                    $entityManager->flush();

                    return $this->redirectToRoute('app_main');
                }
            } elseif ($form->get('non')->isClicked()) {
                // L'utilisateur a annulé le choix
                return $this->redirectToRoute('app_main');
            }
        }

        $annonces = $entityManager->getRepository(Annonce::class)->findAll();
        $user = $entityManager->getRepository(Utilisateur::class)->findOneBy(['noCompte' => $this->getUser()->getId()]);
        $compte = $entityManager->getRepository(Compte::class)->findOneBy(['id' => $this->getUser()]);
        $notes = $compte->getNotes();
        $somme = 0;
        if ($notes != null) {
            foreach ($notes as $note) {
                $somme += intval($note->getNote());
            }
            $n = count($notes);
            $note = -1;
            if ($n > 0) {
                $note = round($somme / $n, 1);
            }
        }
        #dd($annonceCliquee->getDatesAnnonce());

        return $this->render('choisir/choisir.html.twig', [
            'title' => 'EcoPrêt',
            'user' => $this->getUser(),
            'choisirAnnonce' => $form->createView(),
            'annonces' => $annonces,
            'annonceCliquee' => $annonceCliquee,
            'bool_prix' => $bool_prix,
            'no_dispo' => $no_dispo,
            'listeDisponibilite' => $annonceCliquee->getDisponibiliteLisible(),
            'listeAttente' => $formlist->createView(),
            'DejaAjouter' => $DejaAjouter,
            'florins' => $user->getNbFlorains(),
            'note' => $note,
        ]);
    }
}
