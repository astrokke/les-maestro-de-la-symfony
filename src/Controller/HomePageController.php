<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Repository\PhotosRepository;
use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomePageController extends AbstractController
{
    #[Route('/', name: 'app_index')]
    public function index(ProduitRepository $produitRepo, PhotosRepository $photoRepo): Response
    {
        $produits = $produitRepo->findTopPromoProducts();
        $dataPromo = [];
        foreach ($produits as $produit) {
        $prixTTC = $produit->getPrixHT() + ($produit->getPrixHT() * $produit->getTVA()->getTauxTva() / 100);
        // Vérifiez si le produit a une promotion
        if ($produit->getPromotion() !== null) {
            $prixTTC = $prixTTC * $produit->getPromotion()->getTauxPromotion();
        }
        $oldPrice = $produit->getPrixHT() + ($produit->getPrixHT() * $produit->getTVA()->getTauxTva() / 100);
        $photos = $photoRepo->searchPhotoByProduit($produit);
        $dataPromo[] = [
            'produit' => $produit,
            'prixTTC' => $prixTTC,
            'photos' => $photos,
            'oldPrice' => $oldPrice,

        ];
    }

        return $this->render('homepage/indexHomePage.html.twig', [
            'title' => 'MSymfony',
            'subtitle' => 'La musique, c\'est notre passion, les promotions, c\'est notre métier',
            'data' => $dataPromo,
        ]);
    }
}
