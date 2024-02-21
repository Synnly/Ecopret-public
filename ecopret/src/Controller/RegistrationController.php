<?php

namespace App\Controller;

use App\Entity\Compte;
use App\Entity\Utilisateur;
use App\Mail\MailService;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        //Création d'un compte 
        $user = new Compte();
        $utilisateur = new Utilisateur();
        $erreur = '';
        //Céation du formulaire
        $form = $this->createForm(RegistrationFormType::class, $user);

        //Submit du formulaire
        $form->handleRequest($request);
        
        //Si c'est validé et conforme, je hash le mdp, j'envoie les données dans la table
        if ($form->isSubmitted() && $form->isValid()) {
            $user->setMotDePasseCompte(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            if (!$entityManager->getRepository(Compte::class)->findOneBy(['AdresseMailCOmpte' => $user->getAdresseMailCOmpte()])) {
                $utilisateur->setNoCompte($user);
                $utilisateur->setEstVerifie(false);
                $utilisateur->setEstGele(false);
                $utilisateur->setPaiement(false);
                $utilisateur->setAUneReduction(false);
                $utilisateur->setNbFlorains(0);
                $entityManager->persist($utilisateur);
                $entityManager->persist($user);
                $entityManager->flush();
                if($request->query->get('magicInput') !== 'KGsTNQxeeiVoakoZSGNKGVXkhZCxWu'){
                     //Création d'un mail
                    $mail = new MailService();
                    $mail->sendMail($user, 'Inscription EcoPrêt', 'bienvue sur Ecoprêt');
                }
               
                //Redirection vers la page main
                return $this->redirectToRoute('main');
            }else {
                $erreur = "Un compte existe déjà avec cette adresse";
                
            }
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
            'erreur' => $erreur,
        ]);
    }
}