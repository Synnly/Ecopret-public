<?php

namespace App\Controller;

use App\Repository\ConversationRepository;
use App\Repository\MessageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ConversationController extends AbstractController
{
    #[Route('/conversation', name: 'app_conversation')]
    public function index(ConversationRepository $conversationRepository): Response
    {
         $convs = array_merge($conversationRepository->findBy(['participant1' => $this->getUser()]), $conversationRepository->findBy(['participant2' => $this->getUser()]));

        return $this->render('conversation/index.html.twig', [
            'conversations' => $convs
        ]);
    }

    #[Route('/conversation/{id}', name: 'app_conversation_chat')]
    public function conversation(ConversationRepository $conversationRepository, MessageRepository $messageRepository, int $id): Response
    {
        $conv = $conversationRepository->findOneBy(['id' => $id]);
        //Récupération des messages triés par date d'envoi
        $messages = $messageRepository->findBy([
            'conversation' => $conv
        ], ['date' => 'ASC']);

        return $this->render('conversation/chat.html.twig', [
            'conv' => $conv,
            'messages' => $messages
        ]);
    }
}
