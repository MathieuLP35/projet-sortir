<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        if($this->getUser()->isIsActive() === true){

            // Utilisateur non connecté, redirigez-le vers la page '/login'
            return $this->redirectToRoute('app_event_index');
        }

        return $this->render('home/index.html.twig', [
      
        ]);
    }
}
