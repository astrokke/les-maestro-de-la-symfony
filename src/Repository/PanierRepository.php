<?php

namespace App\Repository;

use App\Entity\Panier;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Panier>
 *
 * @method Panier|null find($id, $lockMode = null, $lockVersion = null)
 * @method Panier|null findOneBy(array $criteria, array $orderBy = null)
 * @method Panier[]    findAll()
 * @method Panier[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PanierRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Panier::class);
    }



    public function getLastPanier($id)
    {
        return $this->createQueryBuilder('p')
            ->where('p.Users = :id')
            //->join('p.commande','c')
            //->andWhere('p.commande is NULL')
            ->orderBy('p.id', 'DESC')
            ->setMaxResults(1)
            ->setParameter('id',   $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function getLastPanierCommande($userId)
{
    return $this->createQueryBuilder('p')
        ->leftJoin('p.commande', 'c', 'WITH', 'c.Panier = p.id')
        ->where('p.Users = :userId')
        ->andWhere('c.Panier IS NULL')
        ->orderBy('p.id', 'DESC')
        ->setMaxResults(1)
        ->setParameter('userId', $userId)
        ->getQuery()
        ->getOneOrNullResult();
}


    //    /**
    //     * @return Panier[] Returns an array of Panier objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Panier
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
