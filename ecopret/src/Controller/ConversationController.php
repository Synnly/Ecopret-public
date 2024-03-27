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
        $convs = $conversationRepository->findBy(['participant1' => $this->getUser()]);
        $convs[] = $conversationRepository->findBy(['participant2' => $this->getUser()]);
        $convs = $convs[0];

        return $this->render('conversation/index.html.twig', [
            'conversations' => $convs
        ]);
    }

    #[Route('/conversation/{id}', name: 'app_conversation_chat')]
    public function conversation(ConversationRepository $conversationRepository, MessageRepository $messageRepository, int $id): Response
    {
        $conv = $conversationRepository->findOneBy(['id' => $id]);
        $messages = $conv->getMessages();

        return $this->render('conversation/chat.html.twig', [
            'conv' => $conv,
            'messages' => $messages
        ]);
    }
}
