<?php
namespace App\EventSubscriber;

use App\Entity\Utilisateur;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class UtilisateurGeleSubscriber implements EventSubscriberInterface
{
    private TokenStorageInterface $tokenStorage;
    private UrlGeneratorInterface $urlGenerator;
    private EntityManagerInterface $em;

    public function __construct(TokenStorageInterface $tokenStorage, UrlGeneratorInterface $urlGenerator, EntityManagerInterface $entityManager)
    {
        $this->tokenStorage = $tokenStorage;
        $this->urlGenerator = $urlGenerator;
        $this->em = $entityManager;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST => 'onKernelRequest',
        ];
    }

    public function onKernelRequest(RequestEvent $event)
    {
        $request = $event->getRequest();
        if (!$this->tokenStorage->getToken()) {
            return;
        }

        $user = $this->tokenStorage->getToken()->getUser();
        $utilisateur = $this->em->getRepository(Utilisateur::class)->findOneBy(['noCompte'=>$user]);


        // Vérficiation du gel de l'utilisateur
        if ($user && method_exists($utilisateur, 'isEstGele') && $utilisateur->isEstGele() && $request->attributes->get('_route') !== 'app_degel') {
            $response = new RedirectResponse($this->urlGenerator->generate('app_degel'));
            $event->setResponse($response);
        }
    }
}
