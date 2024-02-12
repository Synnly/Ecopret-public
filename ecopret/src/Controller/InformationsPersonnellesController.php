<?php

namespace App\Controller;

use App\Entity\Compte;
use App\Entity\Lieu;
use App\Form\InformationsPersonnellesFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

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

        $user = $this->getUser();
        $lieu = new Lieu();

        $form = $this->createForm(InformationsPersonnellesFormType::class);
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
