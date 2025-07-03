<?php

namespace App\Repository;

use App\Entity\Book;
use App\Model\BookSearchCriteria;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Book>
 */
class BookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

    public function findBySearchCriteria(BookSearchCriteria $criteria): array
    {
        $qb = $this->createQueryBuilder('b');

        if ($criteria->title) {
            $qb->andWhere('b.title LIKE :title')
                ->setParameter('title', '%' . $criteria->title . '%');
        }

        if ($criteria->author) {
            $qb->andWhere('b.author LIKE :author')
                ->setParameter('author', '%' . $criteria->author . '%');
        }

        if ($criteria->genre) {
            $qb->andWhere('b.genre = :genre')
                ->setParameter('genre', $criteria->genre);
        }

        if ($criteria->rating !== null) {
            $qb->andWhere('b.averageRating >= :rating')
                ->setParameter('rating', $criteria->rating);
        }
        if ($criteria->minPages !== null) {
            $qb->andWhere('b.pages >= :minPages')
                ->setParameter('minPages', $criteria->minPages);
        }

        if ($criteria->maxPages !== null) {
            $qb->andWhere('b.pages <= :maxPages')
                ->setParameter('maxPages', $criteria->maxPages);
        }
        return $qb->getQuery()->getResult();
    }
    //    /**
    //     * @return Book[] Returns an array of Book objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('b')
    //            ->andWhere('b.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('b.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Book
    //    {
    //        return $this->createQueryBuilder('b')
    //            ->andWhere('b.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}