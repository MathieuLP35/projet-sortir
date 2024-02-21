<?php

namespace App\Repository;

use App\Entity\Event;
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
//        $query->andWhere('e.startDatetime >= :date')
//        ->setParameter('date', new \DateTime('-1 month'));

        if (isset($data['sites'])) {
            $query->andWhere('e.sites = :sites')
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
        if (isset($data['limit_register_date'])) {
            $query->andWhere('e.limitRegisterDate <= :limit_register_date')
                ->setParameter('limit_register_date', $data['limit_register_date']);
        }
        if (isset($data['is_registered'])) {
            $query->leftJoin('e.isRegister', 'eu');
            foreach($query->getQuery()->getResult() as $event) {
                foreach($event->getIsRegister() as $user) {
                    if($user->getId() == $data['is_registered']) {
                        $query->andWhere('eu.id = :is_registered')
                            ->setParameter('is_registered', $data['is_registered']);
                    }
                }
            }
        }
        if (isset($data['is_not_registered'])) {
            $query->leftJoin('e.isRegister', 'eu');
            foreach($query->getQuery()->getResult() as $event) {
                foreach($event->getIsRegister() as $user) {
                    if($user->getId() != $data['is_not_registered']) {
                        $query->andWhere('eu.id != :is_not_registered')
                            ->orWhere('e.isRegister IS EMPTY')
                            ->setParameter('is_not_registered', $data['is_not_registered']);
                    }
                }
            }
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
