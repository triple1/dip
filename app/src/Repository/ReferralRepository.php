<?php

namespace App\Repository;

use App\Entity\Participant;
use App\Entity\Provider;
use App\Entity\Referral;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Referral|null find($id, $lockMode = null, $lockVersion = null)
 * @method Referral|null findOneBy(array $criteria, array $orderBy = null)
 * @method Referral[]    findAll()
 * @method Referral[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReferralRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Referral::class);
    }

    public function getNewReferralsCountByProvider(?Provider $provider): int
    {
        if ($provider === null) return 0;
        $dql = '
            select
                count(r.id)
            from
                App\Entity\Referral r
            where
                r.provider = :prov and
                r.status = :status
        ';

        $params = [
            'prov' => $provider,
            'status' => Referral::STATUS_NEW
        ];

        return (int)$this->getEntityManager()
            ->createQuery($dql)
            ->setParameters($params)
            ->getSingleScalarResult();
    }

    public function getReferralsByProvider(Provider $provider): array
    {
        $dql = '
            select
                ref, part, prov
            from
                \App\Entity\Referral ref
            join ref.participant part
            join ref.provider prov
            where
                ref.provider = :prov
            order by ref.id desc
        ';

        $params = [
            'prov' => $provider
        ];

        return $this->getEntityManager()
            ->createQuery($dql)
            ->setParameters($params)
            ->getResult();
    }

    public function getReferralsByParticipant(Participant $participant): array
    {
        $dql = '
            select
                ref, part, prov
            from
                \App\Entity\Referral ref
            join ref.participant part
            join ref.provider prov
            where
                ref.participant = :part
            order by ref.id desc
        ';

        $params = [
            'part' => $participant
        ];

        return $this->getEntityManager()
            ->createQuery($dql)
            ->setParameters($params)
            ->getResult();
    }

    // /**
    //  * @return Referral[] Returns an array of Referral objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Referral
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
