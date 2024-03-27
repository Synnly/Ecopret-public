<?php

namespace App\Controller;

use App\Entity\Message;
use App\Repository\ConversationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Log\Logger;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\PublisherInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;

class MessageController extends AbstractController
{
    #[Route('/message', name: 'app_message', methods: 'POST')]
    public function sendMessage(Request $request, ConversationRepository $conversationRepository, SerializerInterface $serializer, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true); // On récupère les data postées et on les déserialize
        if (empty($contenu = $data['content'])) {
            throw new AccessDeniedHttpException('Pas de message');  // TODO : Retirer l'exception (overkill)
        }
        // Recherche de la conversation
        $conv = $conversationRepository->findOneBy(['id' => $data['conv']]);

        if (!$conv) {
            throw new AccessDeniedHttpException('Conversation non spécifiée');
        }

        $message = new Message();
        $message->setMessage($contenu);
        $message->setExpeditaire($this->getUser());
        $message->setConversation($conv);

        $em->persist($message);
        $em->flush();

        // Serialisation du message
        $jsonMessage = $serializer->serialize($message, 'json', [
            'groups' => ['message']
        ]);

        $update = new Update( 'localhost:8000/conversation/'.$conv->getId(),$jsonMessage,);
        $logger->debug($publisher->publish($update));

        // Envoi du message
        return new JsonResponse($jsonMessage, Response::HTTP_OK, [], true);
    }
}
