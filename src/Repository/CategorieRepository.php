<?php

namespace App\Repository;

use App\Entity\Categorie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\SecurityBundle\Security;

/**
 * @extends ServiceEntityRepository<Categorie>
 *
 * @method Categorie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Categorie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Categorie[]    findAll()
 * @method Categorie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategorieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Categorie::class);
    }

    public function searchCategorieParente(string $name): ?array
    {
        return $this->createQueryBuilder('c')
            ->where('c.libelle like :libelle')
            ->andWhere('c.categorie_parente is null',)
            ->setParameter('libelle', '%' . $name . '%')
            ->getQuery()
            ->getResult();
    }

    public function searchCategorieEnfant($categorie)
    {
        $sql = "select * from categorie c where c.categorie_parente_id = ?";
        $query = $this->getEntityManager()->getConnection()
            ->executeQuery($sql, [$categorie->getId()]);
        $result =  $query->fetchAllAssociative();
        $enfants = [];
        foreach ($result as $cate) {
            $enfant = $this->find($cate['id']);
            $enfants[] = $enfant;
        }
        return $enfants;
    }

    public function searchByName(string $libelle): ?array
    {
        return $this->createQueryBuilder('s')
            ->where('s.libelle like :val')
            ->setParameter('val', '%'.$libelle.'%')
            ->getQuery()
            ->getResult();
    }

    //    /**
    //     * @return Categorie[] Returns an array of Categorie objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('c.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Categorie
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
