<?php

namespace App\Repository;

use App\Entity\Etat;
use App\Entity\Event;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Event>
 *
 * @method Event|null find($id, $lockMode = null, $lockVersion = null)
 * @method Event|null findOneBy(array $criteria, array $orderBy = null)
 * @method Event[]    findAll()
 * @method Event[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Event::class);
    }

    // findByFilter
    public function findByFilter($data)
    {
        $query = $this->createQueryBuilder('e');

        $query->andWhere('e.startDatetime >= :date')
        ->setParameter('date', new \DateTime('-1 month'));

        // cacher tous les événements sauf si je suis participant ou organisateur et les évènement ouvert
        $query->leftJoin('e.registeredUser', 'ru')
            ->leftJoin('e.organiser', 'o')
            ->andWhere('e.etat = :etat')
            ->setParameter('etat', Etat::OPEN)
            ->orWhere('ru = :user OR o = :user')
            ->setParameter('user', $data['user']);

        if (isset($data['sites'])) {
            $query->andWhere('e.site = :sites')
                ->setParameter('sites', $data['sites']);
        }
        if (isset($data['name'])) {
            $query->andWhere('e.name LIKE :name')
                ->setParameter('name', '%'.$data['name'].'%');
        }
        if (isset($data['organiser'])) {
            $query->andWhere('e.organiser = :organiser')
                ->setParameter('organiser', $data['organiser']);
        }
        if (isset($data['start_datetime'])) {
            $query->andWhere('e.startDatetime >= :start_datetime')
                ->setParameter('start_datetime', $data['start_datetime']);
        }
        if (isset($data['end_date'])) {
            $query->andWhere('DATE_ADD(e.startDatetime, e.duration, \'minute\') <= :end_date')
                ->setParameter('end_date', $data['end_date']);
        }
        if (isset($data['is_registered'])) {
            $query->andWhere(':registered_user MEMBER OF e.registeredUser')
                ->setParameter('registered_user', [$data['is_registered']]);
        }
        if (isset($data['is_not_registered'])) {
            $query->andWhere(':registered_user NOT MEMBER OF e.registeredUser')
                ->setParameter('registered_user', [$data['is_not_registered']]);
        }
        if (isset($data['old_event'])) {
            // event is old if the limit register date is passed
            $query->andWhere('e.limitRegisterDate < :old_event')
                ->setParameter('old_event', new \DateTime());
        }

        return $query->getQuery()->getResult();
    }

//    /**
//     * @return Event[] Returns an array of Event objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('e.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Event
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
