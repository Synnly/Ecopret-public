<?php

namespace App\Controller;

use App\Entity\Admin;
use App\Entity\Compte;
use App\Entity\Conversation;
use App\Entity\Prestataire;
use App\Entity\Utilisateur;
use App\Repository\ConversationRepository;
use App\Repository\MessageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ConversationController extends AbstractController
{
    #[Route('/conversation', name: 'app_conversation')]
    public function index(ConversationRepository $conversationRepository,EntityManagerInterface $em): Response
    {
         $convs = array_merge($conversationRepository->findBy(['participant1' => $this->getUser()]), $conversationRepository->findBy(['participant2' => $this->getUser()]));
        $user = $em->getRepository(Utilisateur::class)->findOneBy(['noCompte' => $this->getUser()->getId()]);
        $nbNotif = 0;
        $notifications = $this->getUser()->getNotifications();
        foreach ($notifications as $notification) {
            if ($notification->getStatus() == 0) {
                $nbNotif ++;
            }
        }
        return $this->render('conversation/index.html.twig', [
            'conversations' => $convs,
            'user' => $this->getUser(),
            'florins' => $user->getNbFlorains(),
            'nbNotif' => $nbNotif,
        ]);
    }

    #[Route('/conversation/{id}', name: 'app_conversation_chat')]
    public function conversation(ConversationRepository $conversationRepository, MessageRepository $messageRepository, int $id,EntityManagerInterface $em): Response
    {
        $conv = $conversationRepository->findOneBy(['id' => $id]);

        if($conv == null){
            return $this->redirectToRoute("app_main");
        }

        if(!$conv->estParticipant($this->getUser())){
            return $this->redirectToRoute("app_main");
        }
        //Récupération des messages triés par date d'envoi
        $messages = $messageRepository->findBy([
            'conversation' => $conv
        ], ['date' => 'ASC']);


        $user = $em->getRepository(Utilisateur::class)->findOneBy(['noCompte' => $this->getUser()->getId()]);
        $nbNotif = 0;
        $notifications = $this->getUser()->getNotifications();
        foreach ($notifications as $notification) {
            if ($notification->getStatus() == 0) {
                $nbNotif ++;
            }
        }
        return $this->render('conversation/chat.html.twig', [
            'conv' => $conv,
            'messages' => $messages,
            'user' => $this->getUser(),
            'florins' => $user->getNbFlorains(),
            'nbNotif' => $nbNotif,
        ]);
    }

    #[Route('/conversation/creer/{id}', name: 'app_creer_conversation_chat')]
    public function creerconversation(EntityManagerInterface $em, int $id): Response
    {
        $user = $this->getUser();
        $prest = $em->getRepository(Prestataire::class)->findOneBy(["id"=>$id])->getNoUtisateur()->getNoCompte();
        if($user === $prest) $this->redirectToRoute("app_main");
        $conv = $em->getRepository(Conversation::class)->findByUser1OrUser2($user,$prest);
        if($conv == null){
            $conv = new Conversation();
            $conv->setParticipant1($user);
            $conv->setParticipant2($prest);

            $em->persist($conv);
            $em->flush();
        }
        else{
            $conv = $conv[0];
        }

        return $this->redirectToRoute("app_conversation_chat", ["id" => $conv->getId()]);
    }
    #[Route('/admin/conversation/creer/{id}', name: 'app_admin_creer_conversation_chat')]
    public function adminCreerConversation(EntityManagerInterface $em, int $id): Response
    {
        $user = $this->getUser();
        if($em->getRepository(Admin::class)->findOneBy(["noCompte"=>$user]) == null) $this->redirectToRoute("app_main");
        $dest = $em->getRepository(Compte::class)->findOneBy(["id"=>$id]);
        if($user === $dest) $this->redirectToRoute("app_main");
        $conv = $em->getRepository(Conversation::class)->findByUser1OrUser2($user,$dest);
        if($conv == null){
            $conv = new Conversation();
            $conv->setParticipant1($user);
            $conv->setParticipant2($dest);

            $em->persist($conv);
            $em->flush();
        }
        else{
            $conv = $conv[0];
        }

        return $this->redirectToRoute("app_conversation_chat", ["id" => $conv->getId()]);
    }

}
