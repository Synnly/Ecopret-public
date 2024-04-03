<?php

namespace App\Controller;

use App\Entity\Annonce;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncode;

class DatesAnnoncesController extends AbstractController
{


    #[Route('/dates_annonces/{idAnnonce}', name: 'dates_annonces_request_data_base')]
    public function getDates(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        if ($this->getUser()) {
            // Récupérer le paramètre "id" de l'URL
            $id = $request->attributes->get('idAnnonce');
            $dates = $entityManager->getRepository(Annonce::class)->findOneBy(['id' => $id]);

            return new JsonResponse($dates->getDisponibilite());
        }
    }
}
