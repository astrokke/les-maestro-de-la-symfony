<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Produit;
class ProduitController extends AbstractController
{
    
    #[Route('/produit/{id}', name: 'app_produit')]
    public function show(Produit $produit): Response
    
    {
        return $this->render('produit/index.html.twig', [
            'produit' => $produit,
        ]);
    }
}    
