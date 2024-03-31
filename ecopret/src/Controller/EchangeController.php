<?php

namespace App\Controller;

use App\Entity\Annonce;
use App\Entity\Compte;
use App\Entity\Echange;
use App\Entity\Prestataire;
use App\Entity\Transaction;
use App\Entity\Utilisateur;
use App\Form\EchangeType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Constraints\NotBlank;
use function PHPUnit\Framework\isEmpty;

class EchangeController extends AbstractController
{
    #[Route('/echange/accepter', name: 'app_echange_accepter')]
    public function accepter(EntityManagerInterface $em): Response
    {
        $echanges = $em->getRepository(Echange::class)->findBy(['destinataire'=>$em->getRepository(Annonce::class)->findOneBy(['prestataire'=>$em->getRepository(Prestataire::class)->findOneBy(['noUtisateur'=>$em->getRepository(Utilisateur::class)->findOneBy(['noCompte'=>$this->getUser()])])]),'etat'=>0]);

        return $this->render('echange/accepter.html.twig', [
            'controller_name' => 'EchangeController',
            'echanges' => $echanges
        ]);
    }
    #[Route('/echanges', name: 'app_echanges')]
    public function echanges(EntityManagerInterface $em): Response
    {
        $echanges = $em->getRepository(Echange::class)->findBy(['expeditaire'=>$em->getRepository(Annonce::class)->findOneBy(['prestataire'=>$em->getRepository(Prestataire::class)->findOneBy(['noUtisateur'=>$em->getRepository(Utilisateur::class)->findOneBy(['noCompte'=>$this->getUser()])])])]);

        return $this->render('echange/echanges.html.twig', [
            'controller_name' => 'EchangeController',
            'echanges' => $echanges
        ]);
    }

    #[Route('/echange/{id}', name: 'app_echange')]
    public function index(EntityManagerInterface $em, int $id, Request $request): Response
    {
        $prestataireUser = $em->getRepository(Prestataire::class)->findOneBy(['noUtisateur' => $em->getRepository(Utilisateur::class)->findBy(['noCompte'=>$this->getUser()])]);

        // Le user n'est pas un prestataire
        if($prestataireUser == null){
            return $this->redirectToRoute("app_main");
        }

        $annonceDestinataire = $em->getRepository(Annonce::class)->findOneBy(['id' => $id]);
        $destinataire = $annonceDestinataire->getPrestataire();

        // Echange avec soi meme
        if($destinataire->getId() == $prestataireUser->getId()){
            return $this->redirectToRoute("app_main");
        }

        $choices = [];
        foreach($prestataireUser->getAnnonces() as $annonce){
            $choices[$annonce->getNomAnnonce()." - ".$annonce->getPrix()." florains"] = $annonce->getId();
        }
        //Si l'utilisateur n'a aucune annonce il ne peut pas faire d'échange
        if(empty($choices)){
            return $this->redirectToRoute("app_main");
        }

        $form = $this->createForm(EchangeType::class)
            ->add('annonce', ChoiceType::class, [
                'choices' => $choices,
                'label' => 'Choisissez l\'annonce à échanger',
                'constraints' => [new NotBlank(['message' => 'Veuillez choisir une annonce'])]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Demander un échange'
            ]);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $annonce = $em->getRepository(Annonce::class)->findOneBy(['id' => $form['annonce']->getData()]);
            $echange = $em->getRepository(Echange::class)->findOneBy(['expeditaire' => $annonce, 'destinataire' => $annonceDestinataire]);

            // Un echange existait deja entre ces deux annonces
            if($echange != null){
                return $this->redirectToRoute("app_main");
            }

            $echange = new Echange();
            $echange->setExpeditaire($annonce);
            $echange->setDestinataire($annonceDestinataire);
            $echange->setEtat(0);

            $em->persist($echange);
            $em->flush();

            return $this->redirectToRoute('app_main');
        }

        return $this->render('echange/index.html.twig', [
            'controller_name' => 'EchangeController',
            'form' => $form
        ]);
    }

    #[Route('/echange/accepte/{id}', name: 'app_echange_accepte')]
    public function accepte(EntityManagerInterface $em, int $id): Response
    {
        $echange = $em->getRepository(Echange::class)->findOneBy(['id'=>$id]);
        if($echange != null){

            if($em->getRepository(Prestataire::class)->findOneBy(["noUtisateur"=>$em->getRepository(Utilisateur::class)->findOneBy(['noCompte'=>$this->getUser()])]) !== $echange->getDestinataire()){
                return $this->redirectToRoute('app_main');
            }

            $echange->setEtat(1);
            //TODO : notifier l'utilisateur que sa demande d'échange a été accepté
            $em->persist($echange);

            //On fait deux transactions parce que pourquoi pas
            $transac1 = new Transaction();
            $transac1->setAnnonce($echange->getExpeditaire())->setPrestataire($echange->getExpeditaire()->getPrestataire())->setClient($echange->getDestinataire()->getPrestataire());
            $em->persist($transac1);
            $transac2 = new Transaction();
            $transac1->setAnnonce($echange->getDestinataire())->setPrestataire($echange->getDestinataire()->getPrestataire())->setClient($echange->getExpeditaire()->getPrestataire());
            $em->persist($transac2);

            $em->flush();
        }

         return $this->redirectToRoute('app_echange_accepter');
    }
    #[Route('/echange/refuse/{id}', name: 'app_echange_refuse')]
    public function refuse(EntityManagerInterface $em, int $id): Response
    {
        $echange = $em->getRepository(Echange::class)->findOneBy(['id'=>$id]);
        if($echange != null){

            if($em->getRepository(Prestataire::class)->findOneBy(["noUtisateur"=>$em->getRepository(Utilisateur::class)->findOneBy(['noCompte'=>$this->getUser()])]) !== $echange->getDestinataire()){
                return $this->redirectToRoute('app_main');
            }

            $echange->setEtat(2);
            //TODO : notifier l'utilisateur que sa demande d'échange a été accepté
            $em->persist($echange);
            $em->flush();
        }

        return $this->redirectToRoute('app_echange_accepter');
    }
    #[Route('/echange/annuler/{id}', name: 'app_echange_annuler')]
    public function annuler(EntityManagerInterface $em, int $id): Response
    {
        $echange = $em->getRepository(Echange::class)->findOneBy(['id'=>$id]);

        if($echange != null){
            //On vérifie si l'utilisateur qui veut annuler l'échange est bien celui qui l'a demandé en premier lieu
            if($em->getRepository(Prestataire::class)->findOneBy(["noUtisateur"=>$em->getRepository(Utilisateur::class)->findOneBy(['noCompte'=>$this->getUser()])])  !== $echange->getExpeditaire()->getPrestataire()){
                return $this->redirectToRoute('app_main');
            }

            if($echange->getEtat() != 0){
                return $this->redirectToRoute('app_main');
            }
            $em->remove($echange);
            $em->flush();
        }

        return $this->redirectToRoute('app_echange_accepter');
    }


}
