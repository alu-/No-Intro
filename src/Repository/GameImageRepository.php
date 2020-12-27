<?php

namespace App\Repository;

use App\Entity\GameImage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method GameImage|null find($id, $lockMode = null, $lockVersion = null)
 * @method GameImage|null findOneBy(array $criteria, array $orderBy = null)
 * @method GameImage[]    findAll()
 * @method GameImage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GameImageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GameImage::class);
    }

    // /**
    //  * @return GameImage[] Returns an array of GameImage objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('g.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?GameImage
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
