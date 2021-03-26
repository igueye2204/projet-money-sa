<?php

namespace App\Repository;

use App\Entity\Infotransaction;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Infotransaction|null find($id, $lockMode = null, $lockVersion = null)
 * @method Infotransaction|null findOneBy(array $criteria, array $orderBy = null)
 * @method Infotransaction[]    findAll()
 * @method Infotransaction[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InfotransactionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Infotransaction::class);
    }

    // /**
    //  * @return Infotransaction[] Returns an array of Infotransaction objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Infotransaction
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
