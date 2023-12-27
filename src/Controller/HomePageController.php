<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomePageController extends AbstractController
{
    #[Route('/', name: 'app_index')]
    public function index(ProduitRepository $produitRepo): Response
    {
        $produits = $produitRepo->findAll();


        return $this->render('homepage/indexHomePage.html.twig', [
            'title' => 'Msymfony',
            'produits' => $produits
        ]);
    }
}
