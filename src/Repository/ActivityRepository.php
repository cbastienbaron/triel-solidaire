<?php

namespace App\Repository;

use App\Entity\Activity;
use App\Entity\Tag;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Activity|null find($id, $lockMode = null, $lockVersion = null)
 * @method Activity|null findOneBy(array $criteria, array $orderBy = null)
 * @method Activity[]    findAll()
 * @method Activity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ActivityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Activity::class);
    }

    public function findByTag(Tag $tag)
    {
        return 
            $this
                ->createQueryBuilder('a')
                ->join('a.tags', 'tags')
                ->where('tags.id = :tag')
                ->setParameter('tag', $tag)
                //->orderBy('a.id', 'ASC')
                //->setMaxResults(10)
                ->getQuery()
                //->getResult()
            ;
    }

    public function findAll()
    {
        return 
            $this
                ->createQueryBuilder('a')
                //->orderBy('a.id', 'ASC')
                //->setMaxResults(10)
                ->getQuery()
                //->getResult()
            ;
    }
}
