<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Measurement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Exception;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Measurement>
 *
 * @method Measurement|null find($id, $lockMode = null, $lockVersion = null)
 * @method Measurement|null findOneBy(array $criteria, array $orderBy = null)
 * @method Measurement[]    findAll()
 * @method Measurement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MeasurementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Measurement::class);
    }

    /**
     * @throws Exception
     */
    public function getModuleData(int $moduleId, float $timeStart, float $timeEnd): array
    {
        $sql = "
            select moduleId,
       formattedDate,
       errors,
       measurementCount,
       successes,
       sumValue,
       averageValue,
       maximumValue,
       minimumValue,
       m2.value as lastValue,
       m3.value as lastNotErrorValue
       from (
         select module_id as moduleId,
                DATE_FORMAT(FROM_UNIXTIME(timediv * 15), '%H:%i:%S')       AS formattedDate,
                count(t.id)                                             as measurementCount,
                sum(if(t.value is null, 1, 0))                          as errors,
                sum(if(t.value is not null, 1, 0))                      as successes,
                sum(t.value)                                            as sumValue,
                round(sum(t.value) / sum(if(t.value is null, 0, 1)), 0) as averageValue,
                max(t.value)                                            as maximumValue,
                min(t.value)                                            as minimumValue,
                max(t.id)                                               as maximumId,
                max(case when t.value is not null then t.id end)        as maximumNotErrorId

         from (
                  select *, UNIX_TIMESTAMP(m.date), UNIX_TIMESTAMP(m.date) DIV 15 as timediv
                  from measurement m
                  where m.module_id = :moduleId and UNIX_TIMESTAMP(m.date) between :timeStart and :timeEnd
              ) t
         group by module_id, timediv, timediv * 15
             ) t2
       left join measurement m2 on m2.id = t2.maximumId
       left join measurement m3 on m3.id = t2.maximumNotErrorId
            ";

        return $this
            ->getEntityManager()
            ->getConnection()
            ->executeQuery($sql, [
                'moduleId' => $moduleId,
                'timeStart' => $timeStart,
                'timeEnd' => $timeEnd,
            ])
            ->fetchAllAssociative();
    }
}
