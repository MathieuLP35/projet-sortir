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
    //var_dump('Current Time: ' . $now->format('Y-m-d H:i:s'));

    if ($event->getStartDatetime() > $now) {
        //var_dump('Event name: ' . $event->getName());
        //var_dump('Event Start Time: ' . $event->getStartDatetime()->format('Y-m-d H:i:s'));
        return $this->entityManager->getRepository(Etat::class)->findOneBy(['libelle' => Etat::CREATED]);
    }

    $startDatetime = $event->getStartDatetime();
    $duration = $event->getDuration(); // En minutes
    $eventEndTime = (clone $startDatetime)->modify('+' . $duration . ' minutes');

    if ($now > $startDatetime && $now < $eventEndTime) {
        //var_dump('Event is in progress.');
        return $this->entityManager->getRepository(Etat::class)->findOneBy(['libelle' => Etat::IN_PROGRESS]);
    }

    if ($event->getStartDatetime() > $event->getLimitRegisterDate()) {
        //var_dump('Event is open for registration.');
        return $this->entityManager->getRepository(Etat::class)->findOneBy(['libelle' => Etat::OPEN]);
    }

    if ($now > $event->getStartDatetime()->modify('+' . $event->getDuration() . ' minutes')) {
        var_dump('Event is in the past.');
        return $this->entityManager->getRepository(Etat::class)->findOneBy(['libelle' => Etat::PAST]);
    }

    if ($now > $event->getLimitRegisterDate()) {
        var_dump('Event is closed.');
        return $this->entityManager->getRepository(Etat::class)->findOneBy(['libelle' => Etat::CLOSED]);
    }

    $cancelledState = $this->entityManager->getRepository(Etat::class)->findOneBy(['libelle' => Etat::CANCELLED]);
    if ($cancelledState === null) {
        throw new \RuntimeException("L'état 'CANCELLED' n'a pas été trouvé en base de données.");
    }

    var_dump('Event is cancelled.');
    return $cancelledState;
}

}
