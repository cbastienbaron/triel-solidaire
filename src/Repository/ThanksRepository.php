<?php

namespace App\Repository;

use App\Entity\Thanks;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method TypeOfDonation|null find($id, $lockMode = null, $lockVersion = null)
 * @method TypeOfDonation|null findOneBy(array $criteria, array $orderBy = null)
 * @method TypeOfDonation[]    findAll()
 * @method TypeOfDonation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ThanksRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Thanks::class);
    }


    public function findForSliderHome()
    {
        return 
            $this
                ->createQueryBuilder('t')
                ->andWhere('t.isEnabled = 1')
                ->andWhere('t.file IS NOT NULL')
                ->orderBy('t.createdAt', 'DESC')
                ->setMaxResults(10)
                ->getQuery()
                ->getResult()
            ;   
    }

    public function findEnabled()
    {
        return 
            $this
                ->createQueryBuilder('t')
                ->andWhere('t.isEnabled = 1')
                //->andWhere('t.file IS NOT NULL')
                ->orderBy('t.createdAt', 'DESC')
                //->setMaxResults(10)
                ->getQuery()
                //->getResult()
            ;   
    }

    public function findMerchantEnabled()
    {
        return 
            $this
                ->createQueryBuilder('t')
                ->andWhere('t.isEnabled = 1')
                ->andWhere('t.isMerchant = 1')
                //->andWhere('t.file IS NOT NULL')
                ->orderBy('t.createdAt', 'DESC')
                //->setMaxResults(10)
                ->getQuery()
                //->getResult()
            ;   
    }

    public function findCitizenEnabled()
    {
        return 
            $this
                ->createQueryBuilder('t')
                ->andWhere('t.isEnabled = 1')
                ->andWhere('t.isMerchant = 0')
                //->andWhere('t.file IS NOT NULL')
                ->orderBy('t.createdAt', 'DESC')
                //->setMaxResults(10)
                ->getQuery()
                //->getResult()
            ;   
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
