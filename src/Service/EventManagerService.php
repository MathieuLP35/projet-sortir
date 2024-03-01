<?php

namespace App\Service;

use App\Entity\Etat;
use App\Entity\Event;
use App\Entity\User;
use DateInterval;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class EventManagerService
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function updateEventStates(array $events): void
    {
        foreach ($events as $event) {
            $etat = $this->calculateEventState($event);
            $event->setEtat($etat);
        }

        $this->entityManager->flush();
    }

    private function calculateEventState(Event $event): Etat
    {
        $now = new \DateTime();

        $startDatetime = $event->getStartDatetime();
        $duration = $event->getDuration(); // En minutes

        $eventEndTime = (clone $startDatetime)->modify('+' . $duration . ' minutes');
        if($event->getEtat() != Etat::PAST || $event->getEtat() != Etat::CANCELLED){
            if ($now > $startDatetime && $now < $eventEndTime) {
                return $this->entityManager->getRepository(Etat::class)->findOneBy(['libelle' => Etat::IN_PROGRESS]);
            } else if ($now > $event->getStartDatetime()->modify('+' . $event->getDuration() . ' minutes')){
                return $this->entityManager->getRepository(Etat::class)->findOneBy(['libelle' => Etat::PAST]);
            } else if ($now > $event->getLimitRegisterDate()) {
                return $this->entityManager->getRepository(Etat::class)->findOneBy(['libelle' => Etat::CLOSED]);
            } else{
                return $event->getEtat();
            }
        }else{
            return $event->getEtat();
        }
    }

    /**
     * @throws \Exception
     */
    public function registerUserToEvent(Event $event, UserInterface $user): Response
    {
        $now = new \DateTime();

        if ($event->getRegisteredUser()->contains($user)) {
            if ($now > $event->getLimitRegisterDate()) {
                return new Response('La date limite de désinscription est passé.', 400);
            }

            $event->removeRegisteredUser($user);

            if ($event->getRegisteredUser()->count() < $event->getMaxRegisterQty()) {
                $etat = $this->entityManager->getRepository(Etat::class)->findOneBy(['libelle' => Etat::OPEN]);
                $event->setEtat($etat);
            }
            $this->entityManager->flush();
            return new Response('Vous êtes désinscrit de l\'évènement', 200);
        } else {

            if ($event->getRegisteredUser()->count() >= $event->getMaxRegisterQty()) {
                return new Response('Le nombre maximum de participants est atteint.', 400);
            }

            if ($now > $event->getLimitRegisterDate()) {
                return new Response('La date limite de d\'inscription est passé.', 400);
            }

            $event->addRegisteredUser($user);

            if ($event->getRegisteredUser()->count() >= $event->getMaxRegisterQty()) {
                $etat = $this->entityManager->getRepository(Etat::class)->findOneBy(['libelle' => Etat::CLOSED]);
                $event->setEtat($etat);
            }

            $this->entityManager->flush();
            return new Response('Vous êtes inscrit à l\'évènement', 200);
        }
    }

}
