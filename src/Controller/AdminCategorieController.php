<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Repository\CategorieRepository;
use App\Repository\PhotosRepository;
use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use App\Form\AdminCategorieFormType;


#[Route('admin/')]
class AdminCategorieController extends AbstractController
{

    #[Route('categorie', name: 'app_categorie')]
    public function index(CategorieRepository $caterepo): Response
    {
        $categories = $caterepo->findAll();
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'CategorieController',
            'categories' => $categories
        ]);
    }


    #[Route('categorie/{id}', name: 'app_produit_categorie')]
    public function afficherProduitParCategorie(Categorie $categories,
     ProduitRepository $produitRepo,
      CategorieRepository $categorieRepo,
       PhotosRepository $photorepo): Response
    {
        $categorieId = $categories->getId();
        $categorie = $categorieRepo->find($categorieId);
        $produits = $produitRepo->findProduitsByCategorieId($categorieId);
        $photo = $photorepo->searchPhotoByCategorie($categories);



        return $this->render('admin/produit_categorie.html.twig', [
            'produits' => $produits,
            'categorie' => $categorie,
            'photos' => $photo,
        ]);
    }

    #[Route('categorie_show/{id}', name: 'app_categorie_show_admin')]
    public function show(?Categorie $categorie,
    Security $security,): Response
    {
        if (!$security->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_index');
        }
        if ($categorie === null) {
            return $this->redirectToRoute('app_admin_index');
        }

        return $this->render('admin/categorie_show.html.twig', [
            'title' => 'Fiche d\'une categorie',
            'categorie' => $categorie,
        ]);
    }

    #[Route('categorie_list', name: 'app_categorie_list_admin')]
    public function list(CategorieRepository $categorieRepo,
    ?Categorie $categorie,
    Security $security,
     Request $request): Response
    {
        if (!$security->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_index');
        }
        $categorie = $categorieRepo->searchByName($request->query->get('libelle', ''));

        return $this->render('admin/categorie_list.html.twig', [
            'title' => 'Liste des catégories',
            'categorie' => $categorie,
            'libelle' => $request->query->get('libelle', ''),
        ]);
    }

    #[Route('new_categorie', name: 'app_new_categorie')]
    public function new(Request $request,
     EntityManagerInterface $em,
     Security $security,
    ): Response
    {
        if (!$security->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_index');
        }
        $categorie = new Categorie();
        $form = $this->createForm(AdminCategorieFormType::class, $categorie);

        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $em->persist($categorie);
            $em->flush();
            return $this->redirectToRoute('app_list_admin');
    
        }
        return $this->render('admin/categorie_new.html.twig', [
            'title' => 'Création d\'une nouvelle catégorie',
            'form' => $form->createView(),
        ]);
    }

    #[Route('update_categorie/{id}', name: 'app_update_categorie')]
    public function update(
        Request $request,
        EntityManagerInterface $em,
        ?Categorie $categorie,
        Security $security
    ) {
        if (!$security->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_index');
        }
        if ($categorie === null) {
            return $this->redirectToRoute('app_index');
        }

        $form = $this->createForm(AdminCategorieFormType::class, $categorie);

        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $em->persist($categorie);
            $em->flush();
            return $this->redirectToRoute('app_list_admin');
        }
        return $this->render('admin/new.html.twig', [
            'title' => 'Mise à jour d\'une catégorie',
            'form' => $form,
        ]);
    }

    #[Route('delete_categorie/{id}', name: 'app_delete_categorie', methods: ['POST'])]
    public function delete(Request $request,
     Categorie $categorie,
     Security $security,
      EntityManagerInterface $entityManager): Response
    {
        if (!$security->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_index');
        }
        if ($categorie === null) {
            return $this->redirectToRoute('app_index');
        }
        if ($this->isCsrfTokenValid('delete'.$categorie->getId(), $request->request->get('_token'))) {
            $entityManager->remove($categorie);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_categorie_list_admin', [], Response::HTTP_SEE_OTHER);
    }
}


