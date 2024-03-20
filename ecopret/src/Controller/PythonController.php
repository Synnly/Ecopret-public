<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class PythonController extends AbstractController
{


    #[Route('/pythonRequestDataBase/{mot}', name: 'python_request_data_base')]
    public function getSynonyms(Request $request): JsonResponse
    {
        // Récupérer le paramètre "mot" de l'URL
        $mot = $request->attributes->get('mot');
        // Utilisez $mot comme paramètre pour exécuter votre script Python
        $command = 'python3 js/controllers/main/main.py ' . $mot;
        $output = shell_exec($command);

        // Traitez la sortie du script Python si nécessaire
        return new JsonResponse(['output' => $output]);
    }
}
