<?php

namespace App\Repository;

use App\Entity\MeasurementUnit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MeasurementUnit>
 *
 * @method MeasurementUnit|null find($id, $lockMode = null, $lockVersion = null)
 * @method MeasurementUnit|null findOneBy(array $criteria, array $orderBy = null)
 * @method MeasurementUnit[]    findAll()
 * @method MeasurementUnit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MeasurementUnitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MeasurementUnit::class);
    }

//    /**
//     * @return MeasurementUnit[] Returns an array of MeasurementUnit objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('m.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?MeasurementUnit
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
