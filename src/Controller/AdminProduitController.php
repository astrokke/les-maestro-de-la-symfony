<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Repository\AdminProduitRepository;
use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\SecurityBundle\Security;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\AdminProduitFormType;


#[Route('admin/')]
class AdminProduitController extends AbstractController
{
    #[Route('produit', name: 'app_produit')]
    public function index(ProduitRepository $produitRepo): Response
    {
        $produits = $produitRepo->searchNew();
        return $this->render('produit/index.html.twig', [
            'controller_name' => 'ProduitController',
            'produits' => $produits
        ]);
    }

    #[Route('produit/{id}', name: 'app_produit_show_admin')]
    public function showProducts(?Produit $produit,
    Security $security,): Response
    {
        if (!$security->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_index');
        }

        return $this->render('admin/produit_show.html.twig', [
            'title' => 'Fiche d\'un produit',
            'produit' => $produit,
        ]);
    }

    #[Route('produit_list', name: 'app_produit_list_admin')]
    public function list(AdminProduitRepository $produitRepo,
    Security $security,
    ?Produit $produit,
     Request $request): Response
    {
        
        if (!$security->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_index');
        }
    
        $produit = $produitRepo->searchByName($request->query->get('libelle', ''));

        return $this->render('admin/produit_list.html.twig', [
            'title' => 'Liste des produits',
            'produit' => $produit,
            'libelle' => $request->query->get('libelle', ''),
        ]);
    }

    #[Route('new_produit', name: 'app_new_produit')]
    public function new(Request $request,
     EntityManagerInterface $em,
     Security $security,
    ): Response
    {
        if (!$security->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_index');
        }
        $produit = new Produit();
        $form = $this->createForm(AdminProduitFormType::class, $produit);

        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $em->persist($produit);
            $em->flush();
            return $this->redirectToRoute('app_produit_list_admin');
    
        }
        return $this->render('admin/produit_new.html.twig', [
            'title' => 'Création d\'un nouveau produit',
            'form' => $form->createView(),
        ]);
    }

    #[Route('update_produit/{id}', name: 'app_update_produit')]
    public function update(
        Request $request,
        EntityManagerInterface $em,
        ?Produit $produit,
        Security $security
    ) {
        if (!$security->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_index');
        }
        if ($produit === null) {
            return $this->redirectToRoute('app_index');
        }

        $form = $this->createForm(AdminProduitFormType::class, $produit);

        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $em->persist($produit);
            $em->flush();
            return $this->redirectToRoute('app_produit_list_admin');
        }
        return $this->render('admin/produit_new.html.twig', [
            'title' => 'Mise à jour d\'un produit',
            'form' => $form,
        ]);
    }

    
    #[Route('delete_produit/{id}', name: 'app_delete_produit', methods: ['POST'])]
    public function delete(Request $request,
     Produit $produit,
     Security $security,
      EntityManagerInterface $entityManager): Response
    {
        if (!$security->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_index');
        }
        if ($produit === null) {
            return $this->redirectToRoute('app_index');
        }
        if ($this->isCsrfTokenValid('delete'.$produit->getId(), $request->request->get('_token'))) {
            $entityManager->remove($produit);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_produit_list_admin', [], Response::HTTP_SEE_OTHER);
    }
}