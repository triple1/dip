<?php

namespace App\Repository;

use App\Entity\Provider;
use App\Entity\Service;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Service|null find($id, $lockMode = null, $lockVersion = null)
 * @method Service|null findOneBy(array $criteria, array $orderBy = null)
 * @method Service[]    findAll()
 * @method Service[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ServiceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Service::class);
    }

    public function getServicesByIds(array $ids): array
    {
        $dql = '
            select
                service
            from
                \App\Entity\Service service
            where
                service.id in (:ids)
        ';

        return $this->getEntityManager()
            ->createQuery($dql)
            ->setParameters(['ids' => $ids])
            ->getResult();
    }

    public function getServicesExceptExists(Provider $provider): array
    {
        $dql = '
            select
                service
            from
                \App\Entity\Service service
            where
                 :provider not member of service.providers
        ';

        $params = [
            'provider' => $provider
        ];

        return $this->getEntityManager()
            ->createQuery($dql)
            ->setParameters($params)
            ->getResult();
    }

    // /**
    //  * @return Service[] Returns an array of Service objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Service
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
