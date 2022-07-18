<?php

namespace App\Repository;

use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Book>
 *
 * @method Book|null find($id, $lockMode = null, $lockVersion = null)
 * @method Book|null findOneBy(array $criteria, array $orderBy = null)
 * @method Book[]    findAll()
 * @method Book[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

    public function add(Book $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Book $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    //Je créer ma fonction search dans mon Book repository
    public function searchByWord($search)
    {
        //Le createQueryBuilder est un objet qui permet de créer des requetes SQL en PHP
        $qb = $this->createQueryBuilder('book');

        //Je fais un select sur ma table book
        $query = $qb->select('book')

            //je récupere les livres dont le titre corresponds à :search
            ->where('book.Title LIKE :search')

            //Je dois definir la valeur de :search en lui mettant des "%" avant et apres, cela veux dire que meme si des mots
            // sont avant ou apres la recherche sera reussie
            //Je le fais en 2 étapes qui sont le setParameter et le GetQuery
            ->setParameter('search','%'.$search.'%')
            //Je récupere la requete
            ->getQuery();

        //Enfin j'execute la requete en base de donnée et je récupere les résultats
        return $query->getResult();
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
