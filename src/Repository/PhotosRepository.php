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
    public function searchOnePhotoByProduit($idProduit)
    {

        return $this->createQueryBuilder('p')
            ->where('p.produit = :id')
            ->setParameter('id',   $idProduit)
            ->getQuery()
            ->getOneOrNullResult();
    }
    public function searchPhotoByProduit($idProduit)
    {

        return $this->createQueryBuilder('p')
            ->where('p.produit = :id')
            ->setParameter('id',   $idProduit)
            ->getQuery()
            ->getResult();
    }

    public function insertPhotoWithCategorie($id, $path)
    {
        $sql = "INSERT INTO `photos`(`categorie_id`, `url_photo`) VALUES ('" . $id . "','" . $path . "')";
        $this->getEntityManager()->getConnection()
            ->executeQuery($sql);
    }
    public function insertPhotoWithProduit($id, $path)
    {
        $sql = "INSERT INTO `photos`(`produit_id`, `url_photo`) VALUES ('" . $id . "','" . $path . "')";
        $this->getEntityManager()->getConnection()
            ->executeQuery($sql);
    }

    public function updatePhotoInCategorie($id, $path)
    {
        $sql = "UPDATE `photos` SET url_photo = '$path' WHERE categorie_id =  $id ";
        var_dump($sql);
        $this->getEntityManager()->getConnection()
            ->executeQuery($sql);
    }

    public function updatePhotoInProduit($id, $path)
    {
        $sql = "UPDATE `photos` SET url_photo = '$path' WHERE produit_id =  $id ";
        var_dump($sql);
        $this->getEntityManager()->getConnection()
            ->executeQuery($sql);
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