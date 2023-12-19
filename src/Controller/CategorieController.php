<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Repository\CategorieRepository;
use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategorieController extends AbstractController
{
    
   
    #[Route('/categorie/{id}', name: 'app_produit_categorie')]
    public function afficherProduitParCategorie(Categorie $categories, ProduitRepository $produitRepo, CategorieRepository $categorieRepo): Response
    {
        $categorieId = $categories->getId();
        $categorie = $categorieRepo->find($categorieId);
        $produits = $produitRepo->findProduitsByCategorieId($categorieId);
        

        return $this->render('categorie/produit_categorie.html.twig', [
            'produits' => $produits,
            'categorie' => $categorie,
        ]);
    }
}
