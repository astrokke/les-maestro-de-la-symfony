<?php

namespace App\Controller;

use App\Entity\Produit;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProduitRepository;
use Symfony\Component\HttpFoundation\Request;

class ProduitController extends AbstractController
{  


    #[Route('/produit', name: 'app_produit')]
    public function produit(ProduitRepository $ProduitRepository): Response
    {
        $produit = $ProduitRepository->findAll();
        return $this->render('produit/produit.html.twig', [
            'produit' => $produit,
        ]);
    }

    #[Route('/createProduit', name: 'app_createProduit')]
    public function createProduit(Request $request,ProduitRepository $ProduitRepository ): Response
    {
        $message = '';
        if ($request->getMethod() === "POST") {         
            $libelle = $request->request->get('libelle');
            $description = $request->request->get('description');
            $prixHt = $request->request->get('prixHt');
                
                 
        if ($description === "" || $libelle === "" || $prixHt === "") {
                $message = "les champs ne doivent pas être vide";
            } else {
                $produit = new Produit();
                $produit->setLibelle($libelle);
                $produit->setDescription($description);
                $produit->setPrixHt($prixHt);
                $ProduitRepository->save($produit);
                $message = "le produit à été crée";
            }
        }
        return $this->render('produit/createProduit.html.twig', [
            'message' => $message
        ]);
    }

}
