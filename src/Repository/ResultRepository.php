<?php

namespace App\Repository;

use App\Entity\Result;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Result>
 *
 * @method Result|null find($id, $lockMode = null, $lockVersion = null)
 * @method Result|null findOneBy(array $criteria, array $orderBy = null)
 * @method Result[]    findAll()
 * @method Result[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ResultRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Result::class);
    }

    public function save(Result $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Result $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findLastTirage(): ?array
    {
        return $this->createQueryBuilder('r')
            ->select('CONCAT(r.boule1,\',\',r.boule2,\',\',r.boule3,\',\',r.boule4,\',\',r.boule5) AS tirage1')
            ->addSelect('CONCAT(r.boule1SecondTirage,\',\',r.boule2SecondTirage,\',\',r.boule3SecondTirage,\',\',r.boule4SecondTirage,\',\',r.boule5SecondTirage) AS tirage2')
            ->addSelect('r.numero_chance')
            ->addSelect('STR_TO_DATE(CONCAT(SUBSTRING(r.dateDeTirage,7),\'/\',SUBSTRING(r.dateDeTirage,4,2),\'/\',SUBSTRING(r.dateDeTirage,1,2)),\'%Y/%m/%d\') AS date')
            ->orderBy('STR_TO_DATE(CONCAT(SUBSTRING(r.dateDeTirage,7),\'/\',SUBSTRING(r.dateDeTirage,4,2),\'/\',SUBSTRING(r.dateDeTirage,1,2)),\'%Y/%m/%d\')', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function numberOfOccurence(): ?array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql =
            'WITH r2 AS( SELECT  boule2,COUNT(boule2) AS count_boule2
                            FROM `result` WHERE 1
                            GROUP BY boule2),
                    r3 AS( SELECT  boule3,COUNT(boule3) AS count_boule3
                            FROM `result` WHERE 1
                            GROUP BY boule3),
                    r4 AS( SELECT  boule4,COUNT(boule4) AS count_boule4
                            FROM `result` WHERE 1
                            GROUP BY boule4),
                    r5 AS( SELECT  boule5,COUNT(boule5) AS count_boule5
                            FROM `result` WHERE 1
                            GROUP BY boule5),
                    rd1 AS( SELECT  boule1_second_tirage,COUNT(boule1_second_tirage) AS count_boule1_second_tirage
                            FROM `result` WHERE 1
                            GROUP BY boule1_second_tirage),
                    rd2 AS( SELECT  boule2_second_tirage,COUNT(boule2_second_tirage) AS count_boule2_second_tirage
                            FROM `result` WHERE 1
                            GROUP BY boule2_second_tirage),
                    rd3 AS( SELECT  boule3_second_tirage,COUNT(boule3_second_tirage) AS count_boule3_second_tirage
                            FROM `result` WHERE 1
                            GROUP BY boule3_second_tirage),
                    rd4 AS( SELECT  boule4_second_tirage,COUNT(boule4_second_tirage) AS count_boule4_second_tirage
                            FROM `result` WHERE 1
                            GROUP BY boule4_second_tirage),
                    rd5 AS( SELECT  boule5_second_tirage,COUNT(boule5_second_tirage) AS count_boule5_second_tirage
                            FROM `result` WHERE 1
                            GROUP BY boule5_second_tirage),
                    ra AS( SELECT count(*) AS count
                            FROM `result` WHERE 1
                            )
            SELECT  
                    r.boule1 AS numero,
                    ROUND((COALESCE((r.boule1),0)
                    +COALESCE(r2.count_boule2,0)
                    +COALESCE(r3.count_boule3,0)
                    +COALESCE(r4.count_boule4,0)
                    +COALESCE(r5.count_boule5,0))*100/ra.count,2) AS total,
                    ROUND((COALESCE(rd1.count_boule1_second_tirage,0)
                    +COALESCE(rd2.count_boule2_second_tirage,0)
                    +COALESCE(rd3.count_boule3_second_tirage,0)
                    +COALESCE(rd4.count_boule4_second_tirage,0)
                    +COALESCE(rd5.count_boule5_second_tirage,0))*100/ra.count,2) AS total2,
                    ROUND((COALESCE((r.boule1),0)
                    +COALESCE(r2.count_boule2,0)
                    +COALESCE(r3.count_boule3,0)
                    +COALESCE(r4.count_boule4,0)
                    +COALESCE(r5.count_boule5,0)
                    +COALESCE(rd1.count_boule1_second_tirage,0)
                    +COALESCE(rd2.count_boule2_second_tirage,0)
                    +COALESCE(rd3.count_boule3_second_tirage,0)
                    +COALESCE(rd4.count_boule4_second_tirage,0)
                    +COALESCE(rd5.count_boule5_second_tirage,0))*100/(ra.count*2),2) AS moy
            FROM `result` r
            INNER JOIN ra ON 1=1
            LEFT JOIN r2 ON r.boule1=r2.boule2
            LEFT JOIN r3 ON r.boule1=r3.boule3
            LEFT JOIN r4 ON r.boule1=r4.boule4
            LEFT JOIN r5 ON r.boule1=r5.boule5
            LEFT JOIN rd1 ON r.boule1=rd1.boule1_second_tirage
            LEFT JOIN rd2 ON r.boule1=rd2.boule2_second_tirage
            LEFT JOIN rd3 ON r.boule1=rd3.boule3_second_tirage
            LEFT JOIN rd4 ON r.boule1=rd4.boule4_second_tirage
            LEFT JOIN rd5 ON r.boule1=rd5.boule5_second_tirage
            WHERE 1
            GROUP BY r.boule1
            ORDER BY r.boule1 ASC;

            ';

        $resultSet = $conn->executeQuery($sql,);
        // returns an array of arrays (i.e. a raw data set)
        return $resultSet->fetchAllAssociative();
    }

    public function numberOfOccurenceNumeroChance(): ?array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql =
            '
            WITH ra AS( SELECT count(*) AS count
                            FROM `result` WHERE 1
                            )
            SELECT  
                    r.numero_chance AS numero,
                    ROUND((COALESCE((r.numero_chance),0))*100/ra.count,2) AS total
            FROM `result` r
            INNER JOIN ra ON 1=1
            WHERE 1
            GROUP BY r.numero_chance
            ORDER BY r.numero_chance ASC;

            ';

        $resultSet = $conn->executeQuery($sql,);
        // returns an array of arrays (i.e. a raw data set)
        return $resultSet->fetchAllAssociative();
    }


//    /**
//     * @return Result[] Returns an array of Result objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Result
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
