<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Repository\CategorieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategorieController extends AbstractController
{
    #[Route('/categorie', name: 'app_categorie')]
    public function index(CategorieRepository $caterepo): Response
    {
        $categories = $caterepo->findAll();
        return $this->render('categorie/index.html.twig', [
            'controller_name' => 'CategorieController',
            'categories' => $categories
        ]);
    }

    #[Route('/categorie/id={id}', name: 'app_categorie_show')]
    public function showCategorieParente(Categorie $cate, CategorieRepository $cateRepo, Request $request)
    {
        if ($cate === null) {
            return $this->redirectToRoute('app_index');
        }

        $enfants = $cateRepo->searchCategorieEnfant($cate);



        return $this->render('categorie/showparent.html.twig', [
            'title' => 'Catégorie',
            'cate' => $cate,
            'enfants' => $enfants
        ]);
    }
    #[Route('/enfant{id}', name: 'app_categorie_show_enfant')]
    public function showCategorie(Categorie $cate)
    {
        if ($cate === null) {
            return $this->redirectToRoute('app_index');
        }


        return $this->render('categorie/showenfant.html.twig', [
            'title' => 'Catégorie',
            'cate' => $cate,

        ]);
    }

    public function list(CategorieRepository $cateRepo, Request $request): Response
    {
        $cate = $cateRepo->searchCategorieParente(
            $request->query->get('libelle', ''),

        );
        return $this->render('categorie/_categories.html.twig', [
            'cate' => $cate,
            'libelle' => $request->query->get('libelle', ''),
        ]);
    }
}
