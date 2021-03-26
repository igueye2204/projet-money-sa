<?php

namespace App\Repository;

use App\Entity\TableauDesFrais;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TableauDesFrais|null find($id, $lockMode = null, $lockVersion = null)
 * @method TableauDesFrais|null findOneBy(array $criteria, array $orderBy = null)
 * @method TableauDesFrais[]    findAll()
 * @method TableauDesFrais[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TableauDesFraisRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TableauDesFrais::class);
    }

    // /**
    //  * @return TableauDesFrais[] Returns an array of TableauDesFrais objects
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
    public function findOneBySomeField($value): ?TableauDesFrais
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
