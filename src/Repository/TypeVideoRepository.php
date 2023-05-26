<?php

namespace App\Repository;

use App\Entity\TypeVideo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TypeVideo>
 *
 * @method TypeVideo|null find($id, $lockMode = null, $lockVersion = null)
 * @method TypeVideo|null findOneBy(array $criteria, array $orderBy = null)
 * @method TypeVideo[]    findAll()
 * @method TypeVideo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TypeVideoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TypeVideo::class);
    }

    public function save(TypeVideo $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(TypeVideo $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findLeType($leType){
      $query = $this->createQueryBuilder('e')
        ->select('e.libelleTypeVideo') // to make Doctrine actually use the join
        ->andWhere('e.id = :leType')
        ->setParameter('leType', $leType);
      return $query->getQuery()->getSingleScalarResult();
    }

//    /**
//     * @return TypeVideo[] Returns an array of TypeVideo objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?TypeVideo
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

public function countTypes()
    {
        $qb = $this->createQueryBuilder('u');

        return $qb->select('COUNT(u.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }
}
