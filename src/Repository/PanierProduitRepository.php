<?php

namespace App\Repository;

use App\Entity\PanierProduit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query\Parameter;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PanierProduit>
 *
 * @method PanierProduit|null find($id, $lockMode = null, $lockVersion = null)
 * @method PanierProduit|null findOneBy(array $criteria, array $orderBy = null)
 * @method PanierProduit[]    findAll()
 * @method PanierProduit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PanierProduitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PanierProduit::class);
    }

    public function AddProduitToPanierProduit($idProduit, $idPanier, $qte)
    {
        $sql = "INSERT INTO `panier_produit`(`produit_id`, `panier_id`, `quantite`) VALUES (" . $idProduit . "," . $idPanier . "," . $qte . ")";
        $this->getEntityManager()->getConnection()
            ->executeQuery($sql);
    }
    public function getPanierProduitbyId($Produit, $Panier)
    {
        return $this->createQueryBuilder('p')
            ->where('p.Produit = :idproduit')
            ->andWhere('p.Panier = :idpanier')
            ->orderBy('p.id', 'DESC')
            ->setMaxResults(1)
            ->setParameters(new ArrayCollection([
                new Parameter('idproduit', $Produit),
                new Parameter('idpanier', $Panier),
            ]))
            ->getQuery()
            ->getOneOrNullResult();
    }
    public function updateQuantitéInProduiPanier($qte, $idProduit, $idPanier)
    {
        $sql = "UPDATE `panier_produit` SET `quantite`='$qte' WHERE produit_id = $idProduit and panier_id = $idPanier ";
        $this->getEntityManager()->getConnection()
            ->executeQuery($sql);
    }

    public function removeProduitFromPanier($idProduit, $idPanier)
    {
        return $this->createQueryBuilder('pp')
            ->delete()
            ->where('pp.produit = :idProduit')
            ->andWhere('pp.panier = :idPanier')
            ->setParameter('idProduit', $idProduit)
            ->setParameter('idPanier', $idPanier)
            ->getQuery();

        return $qb->execute();
    }


    //    /**
    //     * @return PanierProduit[] Returns an array of PanierProduit objects
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

    //    public function findOneBySomeField($value): ?PanierProduit
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
