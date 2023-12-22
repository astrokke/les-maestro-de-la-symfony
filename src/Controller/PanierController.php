<?php

namespace App\Controller;

use App\Entity\Panier;
use App\Entity\PanierProduit;
use App\Entity\Users;
use App\Repository\PanierRepository;
use App\Repository\PhotosRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
        $panier = $panierRepo->getLastPanier($id);
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

            return $this->redirectToRoute('app_categorie_list_admin', [], Response::HTTP_SEE_OTHER);
        }
    }
}
