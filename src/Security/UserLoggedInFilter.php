<?php

namespace App\Security;

use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\SecurityBundle\Security;

class UserLoggedInFilter
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function onKernelRequest(RequestEvent $event)
    {
        // Vérifier si l'utilisateur n'est pas connecté
        if (!$this->security->getUser()) {
            // Vérifier si la route est différente de celle de connexion
            if ($event->getRequest()->attributes->get('_route') !== 'app_login') {
                // Rediriger vers la page de connexion
                $response = new RedirectResponse('/login'); // Remplacez '/login' par l'URL de votre page de connexion
                $event->setResponse($response);
            }
        }
    }
}