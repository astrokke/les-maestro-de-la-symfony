<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Repository\CategorieRepository;
use App\Repository\PanierProduitRepository;
use App\Repository\PanierRepository;
use App\Repository\PhotosRepository;
use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
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
        return $this->render('produit/show.html.twig', [
            'title' => 'Fiche d\'un produit',
            'produit' => $produit,
            'prixTTC' => $prixTTC,
            'photos' => $photos
        ]);
    }

    #[Route('/addproduit/{id}', name: "app_add_produit_to_panier")]
    public function addToPanier(
        Security $security,
        Produit $produit,
        PanierRepository $panierRepo,
        Request $request,
        PanierProduitRepository $panierProduitRepo
    ) {
        if (!$security->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('app_index');
        }
        if ($produit === null) {
            return $this->redirectToRoute('app_produit');
        }
        $idProduit = $produit->getId();
        $Panier = $panierRepo->getLastPanier($security->getUser()->getId());
        $idPanier = $Panier->getId();
        $produitInPanier = $panierProduitRepo->getPanierProduitbyId($produit, $Panier);
        if (is_null($produitInPanier)) {
            if ($this->isCsrfTokenValid('addToPanier' . $produit->getId(), $request->request->get('_token'))) {
                if ($this->isCsrfTokenValid('addToPanier' . $produit->getId(), $request->request->get('_token'))) {
                    $panierProduitRepo->AddProduitToPanierProduit($idProduit, $idPanier, 1);
                }

                return $this->redirectToRoute('app_show_produit', ['{id}' => $idProduit = $produit->getId()], Response::HTTP_SEE_OTHER);
            }
        } else {
            if ($this->isCsrfTokenValid('addToPanier' . $produit->getId(), $request->request->get('_token'))) {
                if ($this->isCsrfTokenValid('addToPanier' . $produit->getId(), $request->request->get('_token'))) {
                    $qte = $produitInPanier->getQuantite();
                    $qte++;
                    $panierProduitRepo->updateQuantitéInProduiPanier($qte, $idProduit, $idPanier);
                }
                return $this->redirectToRoute('app_show_produit', ['id' => $idProduit], Response::HTTP_SEE_OTHER);
            }
        }
    }
}
