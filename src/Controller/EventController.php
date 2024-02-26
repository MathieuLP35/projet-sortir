<?php

namespace App\Controller;

use App\Entity\Etat;
use App\Entity\Event;
use App\Form\CancelEventType;
use App\Form\EventFilterType;
use App\Form\EventType;
use App\Repository\EtatRepository;
use App\Repository\EventRepository;
use Doctrine\ORM\EntityManagerInterface;
use MobileDetectBundle\DeviceDetector\MobileDetectorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/event')]
class EventController extends AbstractController
{
    #[Route('/', name: 'app_event_index', methods: ['GET', 'POST'])]
    public function index(Request $request, EventRepository $eventRepository,EntityManagerInterface $entityManager): Response
    {

        $data = [];
        $events = $eventRepository->findByFilter($data);
        $etats = $entityManager->getRepository(Etat::class)->findOneBy(['libelle' => 'Fermé']);
        $form = $this->createForm(EventFilterType::class);
        $form->handleRequest($request);
      
        foreach( $events as $event){
            if($event->getStartDatetime() < new \Datetime()){
                $event->setEtats($etats);
                $entityManager->flush();
            }
        }


        if ($form->isSubmitted()) {
            if ($form->get('sites')->getData()) { $data['sites'] = $form->get('sites')->getData(); }
            if ($form->get('event')->getData()) { $data['name'] = $form->get('event')->getData(); }
            if ($form->get('startDate')->getData()) { $data['start_datetime'] = $form->get('startDate')->getData(); }
            if ($form->get('endDate')->getData()) { $data['limit_register_date'] = $form->get('endDate')->getData(); }
            if ($form->get('organiser')->getData()) { $data['organiser'] = $this->getUser()->getId(); }
            if ($form->get('isRegistered')->getData()) { $data['is_registered'] = $this->getUser()->getId(); }
            if ($form->get('isNotRegistered')->getData()) { $data['is_not_registered'] = $this->getUser()->getId(); }
            if ($form->get('oldEvent')->getData()) { $data['old_event'] = true; }

            $events = $eventRepository->findByFilter($data);

            return $this->render('event/index.html.twig', [
                'events' => $events,
                'form_event_filter' => $form->createView(),
            ]);
        }


        return $this->render('event/index.html.twig', [
            'events' => $events,
            'form_event_filter' => $form->createView(),
        ]);
    }

    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    #[Route('/new', name: 'app_event_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, MobileDetectorInterface $mobileDetector): Response
    {

        if ($mobileDetector->isMobile()) {
            $this->addFlash('danger', 'Vous ne pouvez pas créer une sortie depuis un mobile.');
            return $this->redirectToRoute('app_event_index');
        }

        $etats = $entityManager->getRepository(Etat::class)->findOneBy(['libelle' => 'Ouvert']);
        $event = new Event();
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $event->setOrganiser($this->getUser());
            $event->setEtats($etats);
            $entityManager->persist($event);
            $entityManager->flush();

            return $this->redirectToRoute('app_event_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('event/new.html.twig', [
            'event' => $event,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_event_show', methods: ['GET'])]
    public function show(Event $event): Response
    {

        $participants = $event->getRegisteredUser();
        
        return $this->render('event/show.html.twig', [
            'event' => $event,
            'participants' => $participants
        ]);
    }

    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    #[Route('/{id}/edit', name: 'app_event_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Event $event, EntityManagerInterface $entityManager): Response
    {
        
        // Vérifier si l'utilisateur connecté est l'organisateur de l'événement
        $currentUser = $this->getUser();
        if ($event->getOrganiser() !== $currentUser) {
            $this->addFlash('danger', 'Vous n\'êtes pas autorisé à modifier cette sortie.');
            return $this->redirectToRoute('app_event_index');
        }

        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_event_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('event/edit.html.twig', [
            'event' => $event,
            'form' => $form,
        ]);
    }



    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    #[Route('/{id}/register', name: 'app_register_for_event', methods: ['GET'])]
    public function registerForEvent(Event $event, EntityManagerInterface $entityManager, UserInterface $user): Response
    {

        // Vérifier si la date de clôture des inscriptions est dépassée
        $now = new \DateTime();
        if ($now > $event->getLimitRegisterDate()) {
            // Rediriger l'utilisateur ou afficher un message d'erreur
            $this->addFlash('danger', 'La date limite d\'inscription est dépassée.');
            return $this->redirectToRoute('app_event_index');
        }

        // code pour gérer l'inscription de l'utilisateur à l'événement
        if (!$event->getRegisteredUser()->contains($user)) {
            // Sinon, l'inscrire à l'événement
            $event->addRegisteredUser($user);
        }

        $entityManager->flush();

        // Redirigez l'utilisateur vers la liste des événements
        return $this->redirectToRoute('app_event_index');
    }

    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    #[Route('/{id}/unregister', name: 'app_unregister_for_event', methods: ['GET'])]
    public function unregisterForEvent(Event $event, EntityManagerInterface $entityManager, UserInterface $user): Response
    {

        // Vérifier si la date de clôture des inscriptions est dépassée
        $now = new \DateTime();
        if ($now > $event->getLimitRegisterDate()) {
            // Rediriger l'utilisateur ou afficher un message d'erreur
            $this->addFlash('danger', 'La date limite de désinscription est dépassée.');
            return $this->redirectToRoute('app_event_index');
        }

        // code pour gérer l'inscription de l'utilisateur à l'événement
        if ($event->getRegisteredUser()->contains($user)) {
            // Si l'utilisateur est déjà inscrit, le désinscrire
            $event->removeRegisteredUser($user);
        }

        $entityManager->flush();

        // Redirigez l'utilisateur vers la liste des événements
        return $this->redirectToRoute('app_event_index');
    }

    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    #[Route('/{id}/cancel', name: 'app_cancel_event', methods: ['GET', 'POST'])]
    public function cancelEvent(int $id, Request $request, EntityManagerInterface $entityManager): Response
    {
        $event = $entityManager->getRepository(Event::class)->find($id);
        if (!$event) {
            $this->addFlash('danger', 'Cette sortie n\'existe pas.');
            return $this->redirectToRoute('app_event_index');
        }

        // Vérifier si l'utilisateur connecté est l'organisateur de l'événement
        $currentUser = $this->getUser();
        if ($event->getOrganiser() !== $currentUser) {
            $this->addFlash('danger', 'Vous n\'êtes pas autorisé à annuler cette sortie.');
            return $this->redirectToRoute('app_event_index');
        }

        
        // Créez le formulaire en passant l'événement en tant qu'option
        $form = $this->createForm(CancelEventType::class, null, ['event' => $event]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Traitez le formulaire et effectuez l'annulation ici
            $data = $form->getData();

            // Mettez à jour les informations d'annulation de l'événement
            $event->setEtats($entityManager->getRepository(Etat::class)->findOneBy(['libelle' => 'Annulé']));
            $event->setEventInfos(sprintf(
                "Événement annulé par l'organisateur. Motif : %s",
                $data['cancellationReason']
            ));
            $entityManager->flush();

            // Redirigez l'utilisateur vers la page de l'événement annulé, ou une autre page de confirmation
            return $this->redirectToRoute('app_event_index');
        }

        return $this->render('event/cancel_event.html.twig', [
            'form' => $form->createView(),
            'event' => $event,
        ]);
    }
}