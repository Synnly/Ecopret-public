<?php

namespace App\Controller;

use App\Entity\Compte;
use App\Entity\Lieu;
use App\Form\InformationsPersonnellesFormType;
use App\Repository\CompteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use PDO;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class InformationsPersonnellesController extends AbstractController
{
    #[Route('/infos', name: 'app_infos')]
    public function afficherInformations(EntityManagerInterface $entityManager): Response
    {
        if(!$this->getUser()){
            return $this->redirectToRoute('app_login');
        }
        $user = $this->getUser();

        // Si le lieu n'a pas encore été défini
        if($entityManager->getRepository(Compte::class)->findOneBy(['id' => $user])->getLieu()->isEmpty()){
            return $this->redirectToRoute('app_infos_modif');
        }


        return $this->render('informations_personnelles/informations_personnelles.html.twig', [
            'nom' => $entityManager->getRepository(Compte::class)->findOneBy(['id' => $user])->getNomCompte(),
            'prenom' => $entityManager->getRepository(Compte::class)->findOneBy(['id' => $user])->getPrenomCompte(),
            'mail' => $entityManager->getRepository(Compte::class)->findOneBy(['id' => $user])->getAdresseMailCOmpte(),
            'lieu' => (($lieu = $entityManager->getRepository(Compte::class)->findOneBy(['id' => $user])->getLieu()) == null ? 'N/A': $lieu)
        ]);
    }

    #[Route('/infos/modif', name:'app_infos_modif')]
    public function modifierInformations(Request $request, EntityManagerInterface $entityManager): Response
    {
        if(!$this->getUser()){
            return $this->redirectToRoute('app_login');
        }

        // Connexion bdd
        try{
            $pdo = new PDO('mysql:host=127.0.0.1:3306;dbname=ecopret', 'root');
        }
        catch(Exception $e){
            exit($e->getMessage());
        }

        // Récupération des lieux
        $sql = "SELECT nom_lieu FROM lieu ORDER BY nom_lieu ASC";
        $resultat = $pdo->prepare($sql);
        $resultat->execute();
        $villes = array();
        foreach($resultat as $row => $nom) {
            if ($row != null) $villes[$nom[0]] = $nom[0];
        }

        $user = $entityManager->getRepository(Compte::class)->findOneBy(['id' => $this->getUser()]);

        $form = $this->createForm(InformationsPersonnellesFormType::class)
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
                'attr' => ['autocomplete' => 'new-password',
                            'value' => $user->getMotDePasseCompte()],
                'constraints' => [
                    new NotBlank(['message' => 'Le mot de passe est requis.']),
                    new Regex([
                        'pattern' => '/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d).{8,}$/',
                        'message' => 'Le mot de passe doit contenir au moins 8 caractères dont une majuscule, une minuscule et un chiffre.'
                    ])
                ],
            ])
            ->add('AdresseMailCOmpte', TextType::class, [
                'attr' => ['value' => $user->getAdresseMailCOmpte()],
                'constraints' => [

                    //Le champs ne doit pas être vide sinon envoie du message
                    new NotBlank(['message' => 'L\'adresse mail est requise.']),

                    //Regex pour tester l'adresse mail
                    new Regex([
                        'pattern' => '/^[a-zA-Z]([a-zA-Z0-9-]*\.)?[a-zA-Z0-9-]+@[a-zA-Z-]+\.[a-zA-Z]{2,}$/',
                        'message' => 'Votre adresse mail n\' est pas valide.'
                    ])
                ],
            ])
            ->add('lieu', ChoiceType::class, [
                'choices' => $villes,
                'compound' => true,
                'constraints' => [new NotBlank(['message' => 'Le lieu est requis.'])]
            ]);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            return $this->redirectToRoute('app_infos');
        }

        return $this->render('informations_personnelles/form_informations_personnelles.html.twig', [
            'controller_name' => 'InformationsPersonnellesController',
            'InformationsPersonnellesForm' => $form->createView(),
        ]);
    }
}
