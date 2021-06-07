<?php

namespace App\Repository;

use App\Entity\Participant;
use App\Entity\Provider;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Participant|null find($id, $lockMode = null, $lockVersion = null)
 * @method Participant|null findOneBy(array $criteria, array $orderBy = null)
 * @method Participant[]    findAll()
 * @method Participant[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ParticipantRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Participant::class);
    }

    public function getAllForProvider(Provider $provider)
    {
        $dql = '
            select
                part, provs, prov
            from
                \App\Entity\Participant part
            join part.providers provs
            left join part.ownerProvider prov
            where
                :prov member of part.providers
        ';

        $params = [
            'prov' => $provider
        ];

        $query = $this->getEntityManager()
            ->createQuery($dql)
            ->setParameters($params);

        return $query->getResult();
    }


    public function getAll(): array
    {
        $dql = '
            select
                part, prov
            from
                \App\Entity\Participant part
            left join part.providers prov
            order by part.dateCreatedAt desc
        ';

        $r = $this->getEntityManager()
            ->createQuery($dql)
            ->getResult();

        return $r;
    }

    // /**
    //  * @return Participant[] Returns an array of Participant objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Participant
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
