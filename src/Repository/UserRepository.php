<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @extends ServiceEntityRepository<User>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function save(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newHashedPassword);

        $this->save($user, true);
    }


    // public function findCategoriesWithMaxViewsByUser($userId)
    // {
    //     $entityManager = $this->getEntityManager();

    //     $query = $entityManager->createQuery('
    //       SELECT u.id, c.libelleCategorie, v.id, COUNT(v.id) AS nombre_vues
    //       FROM App\Entity\User u
    //       JOIN u.videoPref v
    //       JOIN v.categories c
    //       WHERE u.id = :userId
    //       GROUP BY c.libelleCategorie
    //       HAVING COUNT(v.id) = (
    //           SELECT MAX(vues_par_categorie)
    //           FROM (
    //               SELECT COUNT(v2.id) AS vues_par_categorie
    //               FROM App\Entity\User u2
    //               JOIN u2.videoPref v2
    //               JOIN v2.categories c2
    //               WHERE u2.id = :userId
    //               GROUP BY c2.libelleCategorie
    //           ) AS subquery
    //       )
    //   ');

    //     $query->setParameter('userId', $userId);

    //     return $query->getResult();
    // }


    public function findCategoriesWithMaxViewsByUser($userId)
    {
        $entityManager = $this->getEntityManager();

        // Requête pour obtenir le nombre maximum de vues par catégorie
        $subquery = $entityManager->createQuery('
            SELECT c2.libelleCategorie, COUNT(v2.id) AS vues_par_categorie
            FROM App\Entity\User u2
            JOIN u2.videoPref v2
            JOIN v2.categories c2
            WHERE u2.id = :userId
            GROUP BY c2.libelleCategorie
            ORDER BY vues_par_categorie DESC
        ');

        $subquery->setParameter('userId', $userId);
        $subquery->setMaxResults(1);
        $subquery->setFirstResult(0);

        $maxViewsResult = $subquery->getOneOrNullResult();

        if (!$maxViewsResult) {
            return []; // Aucun résultat trouvé
        }

        $maxViews = $maxViewsResult['vues_par_categorie'];

        // Requête pour obtenir les catégories avec le nombre maximum de vues
        $query = $entityManager->createQuery('
            SELECT c.id
            FROM App\Entity\User u
            JOIN u.videoPref v
            JOIN v.categories c
            WHERE u.id = :userId
            GROUP BY c.libelleCategorie
            HAVING COUNT(v.id) = :maxViews
        ');

        $query->setParameter('userId', $userId);
        $query->setParameter('maxViews', $maxViews);

        return $query->getResult();
    }


//    /**
//     * @return User[] Returns an array of User objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('u.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?User
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
