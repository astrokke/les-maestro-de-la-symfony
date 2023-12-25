<?php

namespace App\Controller;

use App\Entity\Panier;
use App\Entity\PanierProduit;
use App\Entity\Produit;
use App\Entity\Users;
use App\Repository\PanierProduitRepository;
use App\Repository\PanierRepository;
use App\Repository\PhotosRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Common\Collections\Collection;

class PanierController extends AbstractController
{
    #[Route('/panier', name: 'app_panier')]
    public function index(PanierRepository $panierRepo, Security $security, PhotosRepository $photos): Response
    {
        if (!$security->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('app_index');
        }
        $user = $security->getUser();
        $id = $user->getId();
        $panier = $panierRepo->getLastPanierCommande($id);
        if($panier === null){
            $panier = new Panier();
        }
        $produits = [];
        $total = 0;
        foreach ($panier->getPanierProduits() as $lignePanier) {

            $produits[] = [
                'id' => $lignePanier->getId(),
                'produit' => $lignePanier->getProduit(),
                'qte' => $lignePanier->getQuantite(),
                'photo' => $photos->searchOnePhotoByProduit($lignePanier->getProduit()->getId()),
                'prixTTC' => $lignePanier->getProduit()->getPrixHT() + ($lignePanier->getProduit()->getPrixHT() * $lignePanier->getProduit()->getTVA()->getTauxTva() / 100),
            ];
            $total += ($lignePanier->getProduit()->getPrixHT() + ($lignePanier->getProduit()->getPrixHT() * $lignePanier->getProduit()->getTVA()->getTauxTva() / 100)) * $lignePanier->getQuantite();
        }
        return $this->render('panier/index.html.twig', [
            'controller_name' => 'PanierController',
            'produits' => $produits,
            'total' => $total

        ]);
    }
    #[Route('delete_produit_panier/{id}', name: 'app_delete_produit_panier', methods: ['POST'])]
    public function delete(
        Request $request,
        PanierProduit $panierProduit,
        Security $security,
        EntityManagerInterface $entityManager
    ): Response {
        if (!$security->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('app_index');
        }
        if ($panierProduit === null) {
            return $this->redirectToRoute('app_index');
        }
        if ($this->isCsrfTokenValid('delete' . $panierProduit->getId(), $request->request->get('_token'))) {
            if ($this->isCsrfTokenValid('delete' . $panierProduit->getId(), $request->request->get('_token'))) {


                $entityManager->remove($panierProduit);
                $entityManager->flush();
            }

            return $this->redirectToRoute('app_panier', [], Response::HTTP_SEE_OTHER);
        }
    }
    #[Route('remove_produit_panier/{id}', name: 'app_remove_produit_panier', methods: ['POST'])]
    public function remove(
        Security $security,
        Produit $produit,
        PanierRepository $panierRepo,
        PanierProduitRepository $panierProduitRepo,
        Request $request,
    ) {
        if (!$security->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('app_index');
        }
        if ($produit === null) {
            return $this->redirectToRoute('app_index');
        }
        $idProduit = $produit->getId();
        $Panier = $panierRepo->getLastPanierCommande($security->getUser()->getId());
        $idPanier = $Panier->getId();
        $produitInPanier = $panierProduitRepo->getPanierProduitbyId($produit, $Panier);
        if ($this->isCsrfTokenValid('removeToPanier' . $produit->getId(), $request->request->get('_token'))) {
            if ($this->isCsrfTokenValid('removeToPanier' . $produit->getId(), $request->request->get('_token'))) {
                $qte = $produitInPanier->getQuantite();
                $qte--;
                $panierProduitRepo->updateQuantitéInProduiPanier($qte, $idProduit, $idPanier);
            }
            return $this->redirectToRoute('app_panier', [], Response::HTTP_SEE_OTHER);
        }
    }
    #[Route('add_qte_produit_panier/{id}', name: 'app_add_qte_produit_panier', methods: ['POST'])]
    public function addQuantite(
        Security $security,
        Produit $produit,
        PanierRepository $panierRepo,
        PanierProduitRepository $panierProduitRepo,
        Request $request,
    ) {
        if (!$security->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('app_index');
        }
        if ($produit === null) {
            return $this->redirectToRoute('app_index');
        }
        $idProduit = $produit->getId();
        $Panier = $panierRepo->getLastPanier($security->getUser()->getId());
        $idPanier = $Panier->getId();
        $produitInPanier = $panierProduitRepo->getPanierProduitbyId($produit, $Panier);
        if ($this->isCsrfTokenValid('addQteToPanier' . $produit->getId(), $request->request->get('_token'))) {
            if ($this->isCsrfTokenValid('addQteToPanier' . $produit->getId(), $request->request->get('_token'))) {
                $qte = $produitInPanier->getQuantite();
                $qte++;
                $panierProduitRepo->updateQuantitéInProduiPanier($qte, $idProduit, $idPanier);
            }
            return $this->redirectToRoute('app_panier', [], Response::HTTP_SEE_OTHER);
        }
    }
}
