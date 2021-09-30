<?php

namespace App\Repository;

use App\Entity\Beer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Beer|null find($id, $lockMode = null, $lockVersion = null)
 * @method Beer|null findOneBy(array $criteria, array $orderBy = null)
 * @method Beer[]    findAll()
 * @method Beer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BeerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Beer::class);
    }
    public function findByCatTerm(string $term, int $beerId)
    {
        // SELECT c.name, c.id FROM beer as b JOIN category as c WHERE c.term = ... AND b.id = ...
        return $this->createQueryBuilder('b') // b représente Beer
            ->select('c.name, c.id')
            ->join('b.categories', 'c') // on fait une jointure avec l'entité Category en nommant la relation avec son pluriel ou singulier c est un alias
            ->andWhere('c.term=:term AND b.id=:beerId') // les catégories spéciales ou normales d'une bière donnée
            ->setParameter('term', $term)
            ->setParameter('beerId', $beerId)
            ->getQuery()
            ->getResult();
    }

    // /**
    //  * @return Beer[] Returns an array of Beer objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Beer
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
