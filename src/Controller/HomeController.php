<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(Security $security): Response
    {
        // // Vérifiez si l'utilisateur est connecté
        // if ($security->getUser()) {
        //     // Utilisateur connecté, redirigez-le vers la page '/event'
        //     return $this->redirectToRoute('app_event_index');
        // }

        // Utilisateur non connecté, redirigez-le vers la page '/login'
        return $this->redirectToRoute('app_event_index');
    }
}
