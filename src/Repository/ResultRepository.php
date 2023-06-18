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
