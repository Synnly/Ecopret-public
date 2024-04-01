<?php

namespace App\Controller;

use App\Entity\Message;
use App\Repository\ConversationRepository;
use App\Repository\MessageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;

class MessageController extends AbstractController
{
    #[Route('/message/post', name: 'app_message_post', methods: 'POST')]
    public function sendMessage(Request $request, ConversationRepository $conversationRepository, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true); // On récupère les data postées et on les déserialize
        // Recherche de la conversation
        $conv = $conversationRepository->findOneBy(['id' => $data['conv']]);

        if (!$conv) {
            throw new AccessDeniedHttpException('Conversation non spécifiée');
        }

        $message = new Message();
        $message->setMessage($contenu);
        $message->setExpeditaire($this->getUser());
        $message->setEnvoye(true);
        $conv->addMessage($message);

        $em->persist($message);
        $em->persist($conv);
        $em->flush();

        // Envoi du message
        return new JsonResponse("OK", Response::HTTP_OK, [], true);
    }

    #[Route('/message/get', name: 'app_message_get', methods: 'POST')]
    public function getMessage(Request $request,ConversationRepository $conversationRepository, MessageRepository $messageRepository, SerializerInterface $serializer, EntityManagerInterface $entityManager): Response
    {
        $id = json_decode($request->getContent(), true);
        $conv = $conversationRepository->findOneBy(['id' => $id]);
        //Récupération des messages triés par date d'envoi
        $messages = $messageRepository->findBy([
            'conversation' => $conv,
            'lu' => false
        ], ['date' => 'ASC']);

        $jsonMessages = [];

        foreach($messages as $message){
            if($message->getExpeditaire() !== $this->getUser()) {
                $message->setLu(true);
                $entityManager->persist($message);

                $jsonMessages[] = ["message" => $message->getMessage(), "expeditaire" => $message->getExpeditaire()->getId(), "prenom" => $message->getExpeditaire()->getPrenomCompte(), "date" => $message->getDate()];
            }
        }
        $entityManager->flush();

        // Serialisation du message
        $jsonMessage = $serializer->serialize($jsonMessages, 'json', [
            'groups' => ['message']
        ]);


        return new JsonResponse($jsonMessage, 200, [], true);
    }
}
