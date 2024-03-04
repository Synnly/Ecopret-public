<?php

namespace App\Controller;

use App\Entity\Admin;
use App\Entity\Compte;
use Doctrine\ORM\EntityManagerInterface;
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
        ]);
    }
}