<?php

namespace App\Controller;

use App\Entity\Lieu;
use App\Entity\Utilisateur;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class InsertLieuxController extends AbstractController
{
    #[Route('/insert/lieux', name: 'app_insert_lieux')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        include_once ("../public/villes.php");
        set_time_limit(0);  // Pas de limite de temps d'execution
        $nbInsertions = 0;
        foreach($villes as $nomville){
            if($entityManager->getRepository(Lieu::class)->findOneBy(['nom_lieu' => $nomville]) == null) {
                $lieu = new Lieu();
                $lieu->setNomLieu($nomville);
                $entityManager->persist($lieu);
                $entityManager->flush();
                $nbInsertions ++;
            }
        }
        $nbNotif = 0;
        $notifications = $this->getUser()->getNotifications();
        $user = $entityManager->getRepository(Utilisateur::class)->findOneBy(['noCompte' => $this->getUser()->getId()]);

        foreach ($notifications as $notification) {
            if ($notification->getStatus() == 0) {
                $nbNotif ++;
            }
        }
        set_time_limit(30); // Remise de la limite de temps d'execution
        print "$nbInsertions insertions<br>";
        return $this->render('insert_lieux/index.html.twig', [
            'controller_name' => 'InsertLieuxController',
            'user' => $this->getUser(),
            'florins' => $user->getNbFlorains(),
            'nbNotif' => $nbNotif,
        ]);
    }
}
