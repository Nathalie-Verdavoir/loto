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
            'WITH r2 AS( SELECT  boule2,COUNT(boule2) as count_boule2
                            FROM `result` WHERE 1
                            GROUP by boule2),
                    r3 AS( SELECT  boule3,COUNT(boule3) as count_boule3
                            FROM `result` WHERE 1
                            GROUP by boule3),
                    r4 AS( SELECT  boule4,COUNT(boule4) as count_boule4
                            FROM `result` WHERE 1
                            GROUP by boule4),
                    r5 AS( SELECT  boule5,COUNT(boule5) as count_boule5
                            FROM `result` WHERE 1
                            GROUP by boule5),
                    rd1 AS( SELECT  boule1_second_tirage,COUNT(boule1_second_tirage) as count_boule1_second_tirage
                            FROM `result` WHERE 1
                            GROUP by boule1_second_tirage),
                    rd2 AS( SELECT  boule2_second_tirage,COUNT(boule2_second_tirage) as count_boule2_second_tirage
                            FROM `result` WHERE 1
                            GROUP by boule2_second_tirage),
                    rd3 AS( SELECT  boule3_second_tirage,COUNT(boule3_second_tirage) as count_boule3_second_tirage
                            FROM `result` WHERE 1
                            GROUP by boule3_second_tirage),
                    rd4 AS( SELECT  boule4_second_tirage,COUNT(boule4_second_tirage) as count_boule4_second_tirage
                            FROM `result` WHERE 1
                            GROUP by boule4_second_tirage),
                    rd5 AS( SELECT  boule5_second_tirage,COUNT(boule5_second_tirage) as count_boule5_second_tirage
                            FROM `result` WHERE 1
                            GROUP by boule5_second_tirage),
                    ra AS( SELECT count(*) as count
                            FROM `result` WHERE 1
                            )
            SELECT  
                    r.boule1 as numero,
                    ROUND((coalesce((r.boule1),0)
                    +coalesce(r2.count_boule2,0)
                    +coalesce(r3.count_boule3,0)
                    +coalesce(r4.count_boule4,0)
                    +coalesce(r5.count_boule5,0))*100/ra.count,2) AS total,
                    ROUND((coalesce(rd1.count_boule1_second_tirage,0)
                    +coalesce(rd2.count_boule2_second_tirage,0)
                    +coalesce(rd3.count_boule3_second_tirage,0)
                    +coalesce(rd4.count_boule4_second_tirage,0)
                    +coalesce(rd5.count_boule5_second_tirage,0))*100/ra.count,2) AS total2,
                    ROUND((coalesce((r.boule1),0)
                    +coalesce(r2.count_boule2,0)
                    +coalesce(r3.count_boule3,0)
                    +coalesce(r4.count_boule4,0)
                    +coalesce(r5.count_boule5,0)
                    +coalesce(rd1.count_boule1_second_tirage,0)
                    +coalesce(rd2.count_boule2_second_tirage,0)
                    +coalesce(rd3.count_boule3_second_tirage,0)
                    +coalesce(rd4.count_boule4_second_tirage,0)
                    +coalesce(rd5.count_boule5_second_tirage,0))*100/(ra.count*2),2) as moy
            FROM `result` r
            inner join ra ON 1=1
            left join r2 ON r.boule1=r2.boule2
            left join r3 ON r.boule1=r3.boule3
            left join r4 ON r.boule1=r4.boule4
            left join r5 ON r.boule1=r5.boule5
            left join rd1 ON r.boule1=rd1.boule1_second_tirage
            left join rd2 ON r.boule1=rd2.boule2_second_tirage
            left join rd3 ON r.boule1=rd3.boule3_second_tirage
            left join rd4 ON r.boule1=rd4.boule4_second_tirage
            left join rd5 ON r.boule1=rd5.boule5_second_tirage
            WHERE 1
            GROUP by r.boule1
            order by moy desc;

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
