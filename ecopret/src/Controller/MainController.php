<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/main', name: 'app_main')]
    public function index(): Response
    {

        if(!$this->getUser()){
            return $this->redirectToRoute('app_login');
        }
        //Page Main (il me fallait une redirection)
        //Si vous changer la route ou de fichier oublie pas de remplacer RegistrationController.php ligne 46
        return $this->render('main/index.html.twig', [
            'controller_name' => 'EcoPrêt',
            'user' => $this->getUser(),
        ]);
    }
}