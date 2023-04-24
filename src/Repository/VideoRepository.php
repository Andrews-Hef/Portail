<?php

namespace App\Repository;

use App\Entity\Video;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @extends ServiceEntityRepository<Video>
 *
 * @method Video|null find($id, $lockMode = null, $lockVersion = null)
 * @method Video|null findOneBy(array $criteria, array $orderBy = null)
 * @method Video[]    findAll()
 * @method Video[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VideoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Video::class);
    }

    public function findVideoAllFilm(){
      $query = $this->createQueryBuilder('e')
        ->addSelect('r') // to make Doctrine actually use the join
        ->leftJoin('e.typeVideo', 'r')
        ->andWhere('r.libelleTypeVideo = :Film')
        ->setParameter('Film', 'Film')
        ->getQuery();
    return $query->getResult();
    }


    public function findVideoById($id){
      $query = $this->createQueryBuilder('e')
      ->select('e')
      ->where('e.id = :id')
      ->setParameter('id', $id)
      ->getQuery();
      return $query->getSingleResult();
    }
    // public function findAllVideoWithTitre($titres)
    // {
    //     return $this->createQueryBuilder('c')
    //         ->andWhere('c.titre like :query')
    //         ->setParameter('query', "%". $titres ."%")
    //         // ->orderBy('c.id', 'ASC')
    //         // ->setMaxResults(10)
    //         ->getQuery()
    //         ->getResult()
    //     ;
    // }


     


    /**
     * Returns all Annonces per page
     * @return void 
     */
    public function findAllVideoCategories($page, $limit, $filters = null, $typeFilters = null, $titres = null){
      $query = $this->createQueryBuilder('e')
      ->leftJoin('e.categories', 'r')
      ->leftJoin('e.typeVideo', 'a');
      if($titres != null){
        $titres = $titres . "%";
        $query->andWhere('e.titre LIKE :key')
        ->setParameter('key' , '%'.$titres.'%')->getQuery();
      }
      if($filters != null){
        $query->andWhere('r.id IN (:cats)')
          ->setParameter(':cats', array_values($filters))
          ->groupBy('e.id')
          ->having('COUNT(r.id) = :some_count')
          ->setParameter('some_count', count(array_values($filters)));
      }
      if($typeFilters != null){
        $query->andWhere('a.id IN (:type)')
        ->setParameter(':type', array_values($typeFilters));
      }
      $query->setFirstResult(($page * $limit) - $limit)
      ->setMaxResults(100)
    ;
    return $query->getQuery()->getResult();
    }

    /**
     * Returns number of Annonces
     * @return void 
     */
    public function getTotalAnnonces($filters = null, $typeFilters = null, $titres = null){
      $query = $this->createQueryBuilder('a')
          ->select('COUNT(a)')
          ->leftJoin('a.categories', 'r')
          ->leftJoin('a.typeVideo', 'e');
          if($titres != null){
            $titres = $titres . "%";
            $query->andWhere('a.titre LIKE :key')
            ->setParameter('key' , '%'.$titres.'%')->getQuery();
          }
      // On filtre les données
      if($filters != null){
        $query->andWhere('r.id IN (:cats)')
          ->setParameter(':cats', array_values($filters));
      }
      if($typeFilters != null){
        $query->andWhere('e.id IN (:type)')
        ->setParameter(':type', array_values($typeFilters));
      }

      return $query->getQuery()->getSingleScalarResult();
  }

    public function findVideoAllFilmFromOneCategorie($idCategorie, $titres = null){
      $query = $this->createQueryBuilder('e')
        ->addSelect('r') // to make Doctrine actually use the join
        ->leftJoin('e.categories', 'r')
        ->andWhere('r.id = :cateId')
        ->setParameter('cateId', $idCategorie);
        if($titres != null){
          $query->andWhere('e.titre LIKE :key')
          ->setParameter('key' , '%'.$titres.'%')->getQuery();
        }
       return $query->getQuery()->getResult();
    }

    public function findVideoAllFilmFromOneType($idType, $titres = null){
      $query = $this->createQueryBuilder('e')
        ->addSelect('r') // to make Doctrine actually use the join
        ->leftJoin('e.typeVideo', 'r')
        ->andWhere('r.id = :typeId')
        ->setParameter('typeId', $idType);
        if($titres != null){
          $query->andWhere('e.titre LIKE :key')
          ->setParameter('key' , '%'.$titres.'%')->getQuery();
        }
       return $query->getQuery()->getResult();
    }


    public function findVideoAllFilmDemo(){
      $followeeIds = [(1),(11),(20),(16),(51),(44),(17)];
      $query = $this->createQueryBuilder('e')
        ->addSelect('r') // to make Doctrine actually use the join
        ->leftJoin('e.typeVideo', 'r')
        ->andWhere('r.libelleTypeVideo = :Film')
        ->andWhere('e.id in (:listId)')
        ->setParameter('Film', 'Film')
        ->setParameter('listId',  array_values($followeeIds))
        ->setMaxResults(15)
        ->getQuery();
    return $query->getResult();
    }

    public function findVideoAllSerie(){
      $query = $this->createQueryBuilder('e')
        ->addSelect('r') // to make Doctrine actually use the join
        ->leftJoin('e.typeVideo', 'r')
        ->andWhere('r.libelleTypeVideo = :Serie')
        ->setParameter('Serie', 'Série')
        ->getQuery();
    return $query->getResult();
    }

    public function findVideoAllSerieDemo(){
      $followeeIds = [(43),(40),(31),(8),(6),(35),(55)];
      $query = $this->createQueryBuilder('e')
        ->addSelect('r') // to make Doctrine actually use the join
        ->leftJoin('e.typeVideo', 'r')
        ->andWhere('r.libelleTypeVideo = :Serie')
        ->andWhere('e.id in (:listId)')
        ->setParameter('Serie', 'Série')
        ->setParameter('listId',  array_values($followeeIds))
        ->setMaxResults(15)
        ->getQuery();
    return $query->getResult();
    }

    public function findVideoAllAnime(){
      $followeeIds = [(67),(61),(41),(39),(38),(15),(14)];
      $query = $this->createQueryBuilder('e')
        ->addSelect('r') // to make Doctrine actually use the join
        ->leftJoin('e.typeVideo', 'r')
        ->andWhere('r.libelleTypeVideo = :Anime')
        ->setParameter('Anime', 'Animé')
        ->getQuery();
    return $query->getResult();
    }

    public function findVideoAllAnimeDemo(){
      $followeeIds = [(67),(61),(41),(39),(38),(15),(14)];
      $query = $this->createQueryBuilder('e')
        ->addSelect('r') // to make Doctrine actually use the join
        ->leftJoin('e.typeVideo', 'r')
        ->andWhere('r.libelleTypeVideo = :Anime')
        ->andWhere('e.id in (:listId)')
        ->setParameter('Anime', 'Animé')
        ->setParameter('listId',  array_values($followeeIds))
        ->setMaxResults(15)
        ->getQuery();
    return $query->getResult();
    }
    
    public function save(Video $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Video $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    

//    /**
//     * @return Video[] Returns an array of Video objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('v')
//            ->andWhere('v.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('v.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Video
//    {
//        return $this->createQueryBuilder('v')
//            ->andWhere('v.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
