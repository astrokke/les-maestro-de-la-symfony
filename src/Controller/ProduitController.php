<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProduitController extends AbstractController
{
    #[Route('/produit{id}', name: 'app_produit')]
    public function index(): Response
    {
        return $this->render('produit/index.html.twig', [
            'controller_name' => 'ProduitController',
        ]);
    }

    #[Route('/product', name:'app_index_produit')]
    public function list(?Produit $produit)
    {
        return $this->render('produit/index.html.twig', [
            'controller_name' => 'ProduitController',
            'produit' => compact($produit),
        ]);
    }
}
