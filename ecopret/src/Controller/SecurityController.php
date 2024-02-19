<?php

namespace App\Controller;

use App\Form\ResetPasswordRequestFormType;
use App\Form\ResetPasswordFormType;
use App\Repository\CompteRepository;
use App\Mail\MailService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
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

    #[Route('/forgotpswd', name:'forgotten_password')]
    public function forgottenPassword(
        Request $request, 
        CompteRepository $compteRepository, 
        TokenGeneratorInterface $tokenGenerator,
        EntityManagerInterface $entityManager,
        MailService $mailService
    ): Response
    {
        $form = $this->createForm(ResetPasswordRequestFormType::class);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $user = $compteRepository->findOneByEmail($form->get('email')->getData());

            if($user) {
                //Génération d'un token pour créer l'URL
                $token = $tokenGenerator->generateToken();
                $user->setResetToken($token);
                $entityManager->persist($user);
                $entityManager->flush();

                #Génération du lien de réinitialisation
                $url = $this->generateUrl('reset_pass', ['token' => $token], UrlGeneratorInterface::ABSOLUTE_URL);

                //On crée les données de l'email à envoyer
                $context = compact('url', 'user');
                
                //Envoi du mail
                $mail = new MailService();
                $mail->sendMail($user, 'Réinitialisation du mot de passe.', "Bonjour,<br> Pour votre demande, veuillez suivre ce lien afin de réinitialiser votre mot de passe : .$url.");

                return $this->redirectToRoute('main');
            }

            //Si on trouve pas d'utilisateur avec le mail rentré dans le champs
            return $this->redirectToRoute('main');
        }

        return $this->render('security/reset_password_request.html.twig', [
            'requestPassForm' => $form->createView()
        ]);
    }

    #[Route('/forgotpswd/{token}', name:'reset_pass')]
    public function resetPass(
        string $token,
        Request $request,
        CompteRepository $compteRepository,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHaser
    ): Response
    {
        //Vérifier si le token en argument existe en BDD
        $user = $compteRepository->findOneByResetToken($token);
        
        if($user) {
            $form = $this->createForm(ResetPasswordFormType::class);

            $form->handleRequest($request);

            //On efface le token
            if($form->isSubmitted() && $form->isValid()) {
                $user->setResetToken("");
                $user->setMotDePasseCompte(
                    $passwordHaser->hashPassword(
                        $user,
                        $form->get('password')->getData()
                    )
                );

                $entityManager->persist($user);
                $entityManager->flush();

                return $this->redirectToRoute('main');
            }

            return $this->render('security/reset_password.html.twig', [
                'passForm' => $form->createView()
            ]);
        }

        return $this->redirectToRoute('main');
    }
}
