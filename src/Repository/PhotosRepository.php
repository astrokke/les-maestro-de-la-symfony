<?php

namespace App\Repository;

use App\Entity\Photos;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Photos>
 *
 * @method Photos|null find($id, $lockMode = null, $lockVersion = null)
 * @method Photos|null findOneBy(array $criteria, array $orderBy = null)
 * @method Photos[]    findAll()
 * @method Photos[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PhotosRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Photos::class);
    }
    public function searchPhotoByCategorie($categorie)
    {
        $sql = "select * from photos p  where p.categorie_id = ?";
        $query = $this->getEntityManager()->getConnection()
            ->executeQuery($sql, [$categorie->getId()]);
        $result =  $query->fetchAllAssociative();
        $photos = [];
        foreach ($result as $var) {
            $photo = $this->find($var['id']);
            $photos[] = $photo;
        }
        return $photos;
    }
    public function searchPhotoByProduit($idProduit)
    {

        return $this->createQueryBuilder('p')
            ->where('p.Produit = :id')
            ->setParameter('id',   $idProduit)
            ->getQuery()
            ->getOneOrNullResult();
    }
    //    /**
    //     * @return Photos[] Returns an array of Photos objects
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

    //    public function findOneBySomeField($value): ?Photos
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
