<?php

namespace App\Repository;

use App\Entity\EthPrice;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<EthPrice>
 *
 * @method EthPrice|null find($id, $lockMode = null, $lockVersion = null)
 * @method EthPrice|null findOneBy(array $criteria, array $orderBy = null)
 * @method EthPrice[]    findAll()
 * @method EthPrice[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EthPriceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EthPrice::class);
    }

//    /**
//     * @return EthPrice[] Returns an array of EthPrice objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('e.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?EthPrice
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
