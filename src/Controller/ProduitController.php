<?php

namespace App\Controller;

use App\Entity\Panier;
use App\Entity\Produit;
use App\Repository\CategorieRepository;
use App\Repository\PanierProduitRepository;
use App\Repository\PanierRepository;
use App\Repository\PhotosRepository;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
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
        // Vérifiez si le produit a une promotion
        if ($produit->getPromotion() !== null) {
            $prixTTC = $prixTTC * $produit->getPromotion()->getTauxPromotion();
        }
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
        PanierProduitRepository $panierProduitRepo,
        EntityManagerInterface $em
    ) {
        if (!$security->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('app_index');
        }

        if ($produit === null) {
            return $this->redirectToRoute('app_produit');
        }

        $user = $security->getUser();
        $panier = $panierRepo->getLastPanierCommande($user->getId());

        if (!$panier) {
            $panier = new Panier();
            $panier->setUsers($user);
            $em->persist($panier);
            $em->flush();  // Enregistrer le panier en base de données
        }

        $idProduit = $produit->getId();
        $idPanier = $panier->getId();

        $produitInPanier = $panierProduitRepo->getPanierProduitbyId($produit, $panier);

        if ($this->isCsrfTokenValid('addToPanier' . $produit->getId(), $request->request->get('_token'))) {
            if (is_null($produitInPanier)) {
                $panierProduitRepo->AddProduitToPanierProduit($idProduit, $idPanier, 1);
            } else {
                $qte = $produitInPanier->getQuantite() + 1;
                $panierProduitRepo->updateQuantitéInProduiPanier($qte, $idProduit, $idPanier);
            }
            return $this->redirectToRoute('app_show_produit', ['id' => $idProduit], Response::HTTP_SEE_OTHER);
        }
    }
}
