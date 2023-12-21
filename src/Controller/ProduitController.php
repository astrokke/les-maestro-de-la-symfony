<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Repository\CategorieRepository;
use App\Repository\PhotosRepository;
use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class ProduitController extends AbstractController
{
    #[Route('/produit', name: 'app_produit')]
    public function index(ProduitRepository $produitRepo): Response
    {
        $produits = $produitRepo->searchNew();
        return $this->render('produit/index.html.twig', [
            'controller_name' => 'ProduitController',
            'produits' => $produits
        ]);
    }

    #[Route('/produit/{id}', name: 'app_show_produit')]
    public function showProducts(PhotosRepository $photoRepo, ?Produit $produit): Response
    {
        if ($produit === null) {
            return $this->redirectToRoute('app_produit');
        }
        $prixTTC = $produit->getPrixHT() + ($produit->getPrixHT() * $produit->getTVA()->getTauxTva() / 100);
        $photos = $photoRepo->searchPhotoByProduit($produit);
        var_dump($photos);
        return $this->render('produit/show.html.twig', [
            'title' => 'Fiche d\'un produit',
            'produit' => $produit,
            'prixTTC' => $prixTTC,
            'photos' => $photos
        ]);
    }
}
