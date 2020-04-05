<?php

namespace App\Repository;

use App\Entity\TagGroup;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method TagGroup|null find($id, $lockMode = null, $lockVersion = null)
 * @method TagGroup|null findOneBy(array $criteria, array $orderBy = null)
 * @method TagGroup[]    findAll()
 * @method TagGroup[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TagGroupRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TagGroup::class);
    }

    // /**
    //  * @return TagGroup[] Returns an array of TagGroup objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TagGroup
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
