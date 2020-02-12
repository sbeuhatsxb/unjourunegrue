<?php

namespace App\Repository;

use App\Entity\PictureOfTheDay;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method PictureOfTheDay|null find($id, $lockMode = null, $lockVersion = null)
 * @method PictureOfTheDay|null findOneBy(array $criteria, array $orderBy = null)
 * @method PictureOfTheDay[]    findAll()
 * @method PictureOfTheDay[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PictureOfTheDayRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PictureOfTheDay::class);
    }

    // /**
    //  * @return PictureOfTheDay[] Returns an array of PictureOfTheDay objects
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
    public function findOneBySomeField($value): ?PictureOfTheDay
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
