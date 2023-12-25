<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    #[Route('/', name: 'app_index')]
    public function index(ProduitRepository $produitRepo): Response
    {
        $produits = $produitRepo->findAll();


        return $this->render('index/index.html.twig', [
            'title' => 'Listes des promotions',
            'produit' => $produits
        ]);
    }
}