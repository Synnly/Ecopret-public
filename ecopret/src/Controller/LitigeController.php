<?php

namespace App\Controller;

use App\Form\DeclarerLitigeType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class LitigeController extends AbstractController
{
    #[Route('/litige/declarer', name: 'app_decl_litige')]
    public function index(Request $request,EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(DeclarerLitigeType::class);
        $form->handleRequest($request);
        return $this->render('litige/declarer.html.twig', [
            'controller_name' => 'LitigeController',
            'DeclarerLitigeType' => $form->createView(),
        ]);
    }
}
