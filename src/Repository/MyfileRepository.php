<?php

namespace App\Repository;

use App\Entity\Myfile;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Myfile|null find($id, $lockMode = null, $lockVersion = null)
 * @method Myfile|null findOneBy(array $criteria, array $orderBy = null)
 * @method Myfile[]    findAll()
 * @method Myfile[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MyfileRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Myfile::class);
    }

    // /**
    //  * @return Myfile[] Returns an array of Myfile objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Myfile
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
