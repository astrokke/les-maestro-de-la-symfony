<?php

namespace App\Controller;

use App\Entity\Photos;
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
use App\Repository\PhotosRepository;
use App\Service\FileUploader;

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
    #[Route('new_produit', name: 'app_new_produit')]
    public function new(
        Request $request,
        EntityManagerInterface $em,
        Security $security,
        FileUploader $upload,
        PhotosRepository $photo,
        ProduitRepository $produitrepo,
    ): Response {
        if (!$security->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_index');
        }
        $produit = new Produit();
        $form = $this->createForm(AdminProduitFormType::class, $produit);

        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $file = $form['upload_file']->getData();
            if ($file) {
                $file_name = $upload->uploadProduit($file);
                if (null !== $file_name) // for example
                {
                    $directory = $upload->getTargetDirectory();
                    $full_path = $directory . '/' . $file_name;
                } else {
                    $error = 'une erreur est survenue';
                } 
            }
            $categorie = $form['categorie']->getData();
            $produit->setCategorie($categorie);
            
            $em->persist($produit);
            $em->flush();
            $photo->insertPhotoWithProduit($produitrepo->getLastId()->getId(), '/upload/photo_produit/' . $file_name);

            return $this->redirectToRoute('app_produit_list_admin');
        }
        return $this->render('admin/produit_new.html.twig', [
            'title' => 'Création d\'un nouveau produit',
            'form' => $form->createView(),
        ]);
    }

    #[Route('produit_list', name: 'app_produit_list_admin')]
    public function list(
        AdminProduitRepository $produitRepo,
        Security $security,
        ?Produit $produit,
        Request $request
    ): Response {

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

    #[Route('produit_show/{id}', name: 'app_produit_show_admin')]
    public function showProducts(
        ?Produit $produit,
        Security $security,
    ): Response {
        if (!$security->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_index');
        }

        return $this->render('admin/produit_show_admin.html.twig', [
            'title' => 'Fiche d\'un produit',
            'produit' => $produit,
        ]);
    }

    #[Route('update_produit/{id}', name: 'app_update_produit')]
    public function update(
        Request $request,
        EntityManagerInterface $em,
        ?Produit $produit,
        Security $security,
        FileUploader $upload,
        PhotosRepository $photo,
    ) {
        if (!$security->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_index');
        }
        if ($produit === null) {
            return $this->redirectToRoute('app_admin_dashboard');
        }

        $form = $this->createForm(AdminProduitFormType::class, $produit);

        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $file = $form['upload_file']->getData();
            if ($file) {
                $file_name = $upload->uploadProduit($file);
                if (null !== $file_name) // for example
                {
                    $directory = $upload->getTargetDirectory();
                    $full_path = $directory . '/' . $file_name;
                } else {
                    $error = 'une erreur est survenue';
                }
            }
            $categorie = $form['categorie']->getData();
            $produit->getCategorie($categorie);
            $photos=$photo->updatePhotoInProduit($produit->getId(), '/upload/photo_produit/' . $file_name);
            $produit->getPhotos($photos);
            $em->persist($produit);
            $em->flush();
            return $this->redirectToRoute('app_produit_list_admin');
           
            
        }
        return $this->render('admin/produit_new.html.twig', [
            'title' => 'Mise à jour d\'un produit',
            'form' => $form->createView(),
        ]);
    }

    #[Route('delete_produit/{id}', name: 'app_delete_produit', methods: ['POST'])]
    public function delete(
        Produit $produit,
        Security $security,
        EntityManagerInterface $em
    ): Response {
        if (!$security->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_index');
        }
        if ($produit === null) {
            return $this->redirectToRoute('app_admin_dashboard');
        }
        foreach ($produit->getPanierProduits() as $panierProduit) {
            $em->remove($panierProduit); // ou $em->detach($panierProduit);
        }
            $em->remove($produit);
            $em->flush();
            return $this->redirectToRoute('app_produit_list_admin');
        }

    
}
    
   

   
   
    
   


    
