<?php

namespace App\Repository;

use App\Entity\Meal;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Meal>
 *
 * @method Meal|null find($id, $lockMode = null, $lockVersion = null)
 * @method Meal|null findOneBy(array $criteria, array $orderBy = null)
 * @method Meal[]    findAll()
 * @method Meal[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MealRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Meal::class);
    }

    public function save(Meal $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Meal $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getFilteredMealsQuery($params)
    {
        $category = $params['category'] ?? null;
        $tags = $params['tags'] ?? null;
        $diffTime = $params['diffTime'] ?? null;

        $qb = $this->createQueryBuilder('m');

        if ($category) {
            if(is_integer($category)) {
                $qb->andWhere('m.category=:category')
                    ->setParameter('category', $category);
            }

            if($category == 'NULL') {
                $qb->andWhere('m.category IS NULL');
            }

            if($category == '!NULL') {
                $qb->andWhere('m.category IS NOT NULL');
            }
        }

        if ($tags) {
            foreach ($tags as $tag) {
                $qb->join('m.tags', "t$tag")
                    ->andWhere("t$tag = :tag$tag")
                    ->setParameter("tag$tag", $tag);
            }
        }

        if ($diffTime) {
            $diffTime = date('Y-m-d H:i:s', intval($diffTime));
            $qb->andWhere('m.createdAt < :diffTime')
                ->setParameter('diffTime', $diffTime);
        } else {
            $qb->andWhere('m.deletedAt IS NULL');
        }

        return $qb->getQuery();
    }

//    /**
//     * @return Meal[] Returns an array of Meal objects
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

//    public function findOneBySomeField($value): ?Meal
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
