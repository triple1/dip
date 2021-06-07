<?php

namespace App\Repository;

use App\Entity\News;
use App\Entity\Participant;
use App\Entity\Provider;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method News|null find($id, $lockMode = null, $lockVersion = null)
 * @method News|null findOneBy(array $criteria, array $orderBy = null)
 * @method News[]    findAll()
 * @method News[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NewsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, News::class);
    }

    public function getAllNewsByProvider(Provider $provider)
    {
        $participants = $this->getEntityManager()->getRepository(Participant::class)->getAllForProvider($provider);
        $providers = [];
        /** @var Participant $part */
        foreach ($participants as $part) {
            $providers = array_merge($providers, $part->getProviders()->toArray());
        }

        $dql = '
            select
                news, prov
            from
                \App\Entity\News news
            join news.provider prov
            where
                news.provider in (:providers)
            order by prov.id desc
        ';

        $params = [
            'providers' => $providers
        ];

        return $this->getEntityManager()
            ->createQuery($dql)
            ->setParameters($params)
            ->getResult();
    }

    // /**
    //  * @return News[] Returns an array of News objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('n.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?News
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
