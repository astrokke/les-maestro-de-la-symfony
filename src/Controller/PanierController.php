<?php

namespace App\Controller;

use App\Entity\Panier;
use App\Entity\Users;
use App\Repository\PanierRepository;
use App\Repository\PhotosRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PanierController extends AbstractController
{
    #[Route('/panier', name: 'app_panier')]
    public function index(PanierRepository $panierRepo, Security $security, PhotosRepository $photos): Response
    {
        $user = $security->getUser();
        $id = $user->getId();
        $panier = $panierRepo->getLastPanier($id);
        $produits = [];
        foreach ($panier->getPanierProduits() as $lignePanier) {
            $produits[] = ['produit' => $lignePanier->getProduit(), 'qte' => $lignePanier->getQuantite(), 'photo' => $photos->searchPhotoByProduit($lignePanier->getProduit()->getId())];
        }
        return $this->render('panier/index.html.twig', [
            'controller_name' => 'PanierController',
            'produits' => $produits,

        ]);
    }
}
