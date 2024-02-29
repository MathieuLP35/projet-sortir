<?php

namespace App\Service;

use App\Entity\Etat;
use App\Entity\Event;
use DateInterval;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;

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
}
