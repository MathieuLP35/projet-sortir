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

        if ($event->getStartDatetime() > $now) {
            return $this->entityManager->getRepository(Etat::class)->findOneBy(['libelle' => 'Créée']);
        }

        $startDatetime = $event->getStartDatetime();
        $duration = $event->getDuration(); // En minutes

        $newDatetime = (clone $startDatetime)->modify('+' . $duration . ' minutes');

        if ($now < $newDatetime && $now > $startDatetime) {
            return $this->entityManager->getRepository(Etat::class)->findOneBy(['libelle' => 'Activité en cours']);
        }

        if ($event->getStartDatetime() > $event->getLimitRegisterDate()) {
            return $this->entityManager->getRepository(Etat::class)->findOneBy(['libelle' => 'Ouvert']);
        }


        if ($now > $event->getStartDatetime() + $event->getDuration()) {
            return $this->entityManager->getRepository(Etat::class)->findOneBy(['libelle' => 'Passée']);
        }

        if ($now > $event->getLimitRegisterDate()) {
            return $this->entityManager->getRepository(Etat::class)->findOneBy(['libelle' => 'Clôturée']);
        }

        $cancelledState = $this->entityManager->getRepository(Etat::class)->findOneBy(['libelle' => 'Annulée']);
        if ($cancelledState === null) {
            throw new \RuntimeException("L'état 'CANCELLED' n'a pas été trouvé en base de données.");
        }

        return $cancelledState;
    }
}
