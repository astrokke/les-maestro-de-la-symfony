<?php

namespace App\Controller;
use App\Entity\Produit;
use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Common\Collections\Collection;

class PanierController extends AbstractController
{
    #[Route('/panier', name: 'app_panier')]
    // public function index(): Response
    // {
    //     return $this->render('panier/index.html.twig', [
    //         'controller_name' => 'PanierController',
    //     ]);
    // }

   
    public function afficherProduit( ProduitRepository $produits): Response
    {
        $produitId = $produits->getId();
        $produit = $produits->find($produitId);
   

        var_dump($produitId);


        return $this->render('panier/index.html.twig', [
            var_dump($produitId),
            'produit' => $produits,
        
        ]);
    }
}