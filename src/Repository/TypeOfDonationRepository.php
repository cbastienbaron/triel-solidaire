<?php

namespace App\Repository;

use App\Entity\TypeOfDonation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method TypeOfDonation|null find($id, $lockMode = null, $lockVersion = null)
 * @method TypeOfDonation|null findOneBy(array $criteria, array $orderBy = null)
 * @method TypeOfDonation[]    findAll()
 * @method TypeOfDonation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TypeOfDonationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TypeOfDonation::class);
    }

    // /**
    //  * @return TypeOfDonation[] Returns an array of TypeOfDonation objects
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
    public function findOneBySomeField($value): ?TypeOfDonation
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
