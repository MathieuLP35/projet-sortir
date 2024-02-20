<?php

namespace App\Controller;

use App\Entity\Event;
use App\Form\RegisterEventFormType;
use App\Repository\EventRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EventController extends AbstractController
{
    #[Route('/event', name: 'app_event')]
    public function index(EventRepository $eventRepository): Response
    {
        return $this->render('event/index.html.twig', [
            'events' => $eventRepository,
        ]);
    }
    #[Route('/register-for-event', name: 'register_for_event')]
    public function registerForEvent(Request $request, EntityManagerInterface $entityManager): Response
    {
        $event = new Event();
        $form = $this->createForm(RegisterEventFormType::class, $event);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Traitement de l'inscription à l'événement
            // Enregistrement en base de données, etc.

            $entityManager->persist($event);
            $entityManager->flush();

            // Rediriger ou faire d'autres actions après l'inscription
        }

        return $this->render('event/register_for_event.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
