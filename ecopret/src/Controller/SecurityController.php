<?php

namespace App\Controller;

use App\Entity\Compte;
use App\Form\SupprimerCompteFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('main');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
       $this->redirectToRoute('main');
    }
    #[Route('/infos/supprimer', name: 'app_supprimer_compte')]
    public function supprimerCompte(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Si l'utilisateur n'est pas connecté on le redirige vers la page de connection
        if(!$this->getUser()){
            return $this->redirectToRoute('app_login');
        }

        $form = $this->createForm(SupprimerCompteFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('valider')->isClicked()) {
                // L'utilisateur a confirmé la suppression du compte
                //Récupération de l'utilisateur courant
                $user = $entityManager->getRepository(Compte::class)->findOneBy(['id' => $this->getUser()]);

                $session = new Session();
                $session->invalidate();

                $entityManager->remove($user);
                $entityManager->flush();

                return $this->redirectToRoute('app_main');

            } elseif ($form->get('annuler')->isClicked()) {
                // L'utilisateur a annulé la suppression du compte
                return $this->redirectToRoute('app_main');
            }
        }


        return $this->render('security/supprimer_compte.html.twig', [
            'controller_name' => 'SupprimerCompteController',
            'SupprimerCompteFormType' => $form->createView(),
        ]);
    }
}
