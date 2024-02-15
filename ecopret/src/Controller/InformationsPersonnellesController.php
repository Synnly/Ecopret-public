<?php

namespace App\Controller;

use App\Entity\Compte;
use App\Entity\Lieu;
use App\Entity\Prestataire;
use App\Entity\Utilisateur;
use App\Form\CarteBancaireType;
use App\Form\InformationsPersonnellesType;
use App\Form\ModifierInformationsPersonnellesFormType;
use App\Repository\PrestataireRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use PDO;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Config\Definition\BooleanNode;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\File;

class InformationsPersonnellesController extends AbstractController
{
    /**
     * Affiche la page des informations personnelles
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    #[Route('/infos', name: 'app_infos')]
    public function afficherInformations(Request $request, EntityManagerInterface $entityManager, LoggerInterface $logger): Response
    {
        if(!$this->getUser()){
            return $this->redirectToRoute('app_login');
        }
        $user = $this->getUser();

        // Connexion bdd
        try{
            $pdo = new PDO('mysql:host=127.0.0.1:3306;dbname=ecopret', 'root');
        }
        catch(Exception $e){
            exit($e->getMessage());
        }

        // Récupération des lieux
        $sql = "SELECT * FROM lieu ORDER BY nom_lieu ASC";
        $resultat = $pdo->prepare($sql);
        $resultat->execute();
        $villes = array();
        foreach($resultat as $row => $nom) {
            if ($row != null) $villes[$nom[1]] = $nom[0];
        }

        // Création du formulaire
        $form = $this->createForm(InformationsPersonnellesType::class)
            ->add('lieu', ChoiceType::class, [
                'required' => 'true',
                'mapped' => false,
                'choices' => $villes,
                'constraints' => [new NotBlank(['message' => 'Le lieu est requis.'])]
            ])
            ->add('annonce', ChoiceType::class, [
                    'choices' => [
                        'Oui' => 'oui',
                        'Non' => 'non',
                    ],
                    'mapped' => false,
                    'data' => ($entityManager->getRepository(Utilisateur::class)->findOneBy(['id' => $user]) == null || ($prestataire = $entityManager->getRepository(Prestataire::class)->findOneBy(['no_utilisateur_id' => $entityManager->getRepository(Utilisateur::class)->findOneBy(['id' => $user])->getNoCompte()])) == null ? 'non' : 'oui'),
                    'expanded' => true,
            ])
            ->add('carte_identite', FileType::class, [
                'required' => false,
                'mapped' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '10m',
                        'mimeTypes' => [
                            'image/png',
                            'image/jpeg',
                            'application/pdf'
                        ],
                        'mimeTypesMessage' => 'Veuillez choisir un fichier valide',
                    ])
                ]
            ]);

        $form->handleRequest($request);

        // Traitement du formulaire
        if($form->isSubmitted() && $form->isValid()){

            print $entityManager->getRepository(Compte::class)->findOneBy(['id' => $user])->getId();
            // Si pas d'utilisateur associé au compte, on en crée un
            if($entityManager->getRepository(Utilisateur::class)->findOneBy(['noCompte' => $entityManager->getRepository(Compte::class)->findOneBy(['id' => $user])]) == null){
                $utilisateur = new Utilisateur();
                $utilisateur->setNoCompte($entityManager->getRepository(Compte::class)->findOneBy(['id' => $user]));
                $utilisateur->setEstVerifie(false);
                $utilisateur->setEstGele(false);
                $utilisateur->setPaiement(false);
                $utilisateur->setAUneReduction(false);
                $utilisateur->setNbFlorains(0);
                $entityManager->persist($utilisateur);
            }

            // Si l'utilisateur veut passer prestataire
            if($form->get('annonce')->getData() == "Oui" && $prestataire == null) {
                $prestataire = new Prestataire();
                $prestataire->setNoUtisateur($entityManager->getRepository(Utilisateur::class)->findOneBy(['id' => $user])->getNoCompte());
                $entityManager->persist($prestataire);
            }

            // Si l'utilisateur ne veut plus etre prestataire
            if($form->get('annonce')->getData() == "Non" && $prestataire != null) {
                $entityManager->remove($prestataire);
            }
            // Remplacage de l'ancien lieu par le nouveau
            // A changer si plusieurs lieux utilisés
            if(($lieu = ($compte = $entityManager->getRepository(Compte::class)->findOneBy(['id' => $user]))->getLieu()->first()) != null){
                $compte->removeLieu($lieu);
            }
            $compte->addLieu($entityManager->getRepository(Lieu::class)->findOneBy(['id' => $form->get('lieu')->getData()]));

            // Enregistrement de la carte si elle existe
            if($form['carte_identite']->getData() != null) {

                // Suppression de l'ancien fichier
                if(($nomFichier = glob("\.\./carteIdUtilisateurs/".$compte->getId().".*")) != []){
                    unlink($nomFichier[0]);
                }

                $form['carte_identite']->getData()->move("../carteIdUtilisateurs/", $compte->getId() . "." . $form['carte_identite']->getData()->getClientOriginalExtension());
            }

            $entityManager->flush();
            return $this->redirectToRoute('app_infos');
        }

        return $this->render('informations_personnelles/informations_personnelles.html.twig', [
            'controller_name' => 'InformationsPersonnellesController',
            'InformationsPersonnellesForm' => $form->createView()
        ]);
    }

    /**
     * Affiche la page de modification des informations personnelles
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param UserPasswordHasherInterface $userPasswordHasher
     * @return Response
     */
    #[Route('/infos/modif', name:'app_infos_modif')]
    public function modifierInformations(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        if(!$this->getUser()){
            return $this->redirectToRoute('app_login');
        }

        $user = $entityManager->getRepository(Compte::class)->findOneBy(['id' => $this->getUser()]);

        $form = $this->createForm(ModifierInformationsPersonnellesFormType::class)
            ->add('NomCompte', TextType::class, [
                'attr' => ['value' => $user->getNomCompte()],
                'constraints' => [
                    new NotBlank(['message' => 'Le nom est requis.']),
                    new Regex([
                        'pattern' => '/^[A-Z][A-Z-]{0,18}[A-Z]$/',
                        'message' => 'Votre nom doit commencer par une lettre majuscule puis contenir entre 1 et 19 lettres majuscules ou - .'
                    ])
                ]
            ])
            ->add('PrenomCompte', TextType::class, [
                'attr' => ['value' => $user->getPrenomCompte()],
                'constraints' => [
                    new NotBlank(['message' => 'Le prénom est requis.']),
                    new Regex([
                        'pattern' => '/^[A-Z][A-Za-z-]{0,18}[a-zA-Z]$/',
                        'message' => 'Votre prénom doit commencer par une lettre majuscule puis contenir entre 1 et 19 lettres ou - .'
                    ])
                ],
            ])
            ->add('motDePasseCompte', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank(['message' => 'Le mot de passe est requis.']),
                    new Regex([
                        'pattern' => '/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d).{8,}$/',
                        'message' => 'Le mot de passe doit contenir au moins 8 caractères dont une majuscule, une minuscule et un chiffre.'
                    ])
                ],
            ])
            ->add('AdresseMailCOmpte', EmailType::class, [
                'attr' => ['value' => $user->getAdresseMailCOmpte()],
                'constraints' => [
                    new NotBlank(['message' => 'L\'adresse mail est requise.']),
                    new Regex([
                        'pattern' => '/^[a-zA-Z]([a-zA-Z0-9-]*\.)?[a-zA-Z0-9-]+@[a-zA-Z-]+\.[a-zA-Z]{2,}$/',
                        'message' => 'Votre adresse mail n\' est pas valide.'
                    ])
                ],
            ])
            ->add('carte_credit', CarteBancaireType::class, [
                'attr'=> ['compound' => true,
                ]
            ]);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $user->setNomCompte($form->get('NomCompte')->getData());
            $user->setPrenomCompte($form->get('PrenomCompte')->getData());
            $user->setAdresseMailCOmpte($form->get('AdresseMailCOmpte')->getData());
            $user->setMotDePasseCompte(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('motDePasseCompte')->getData()));
            $entityManager->persist($user);
            $entityManager->flush();
            return $this->redirectToRoute('app_main');
        }

        return $this->render('informations_personnelles/form_informations_personnelles.html.twig', [
            'controller_name' => 'InformationsPersonnellesController',
            'InformationsPersonnellesForm' => $form->createView(),
        ]);
    }
}
