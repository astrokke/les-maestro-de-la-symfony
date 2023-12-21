<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Promotion;
use App\Form\PromotionFormType;
use App\Repository\PromotionRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\SecurityBundle\Security;

#[Route('admin/')]
class AdminPromotionController extends AbstractController
{
    #[Route('promotion', name: 'app_promotion')]
    public function index(PromotionRepository $promotionRepo): Response
    {
        $promotion = $promotionRepo->searchNew();
        return $this->render('admin/dashboard.html.twig', [
            'controller_name' => 'AdminPromotionController',
            'promotion' => $promotion
        ]);
    }

    #[Route('promotion_show/{id}', name: 'app_promotion_show')]
    public function showPromotion(?Promotion $promotion,
    Security $security,): Response
    {
        if (!$security->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_index');
        }

        return $this->render('promotion/promotion_show.html.twig', [
            'title' => 'Fiche d\'une promotion',
            'promotion' => $promotion,
        ]);
    }

    #[Route('promotion_list', name: 'app_promotion_list')]
    public function list(PromotionRepository $promotionRepo,
    Security $security,
    ?Promotion $promotion,
     Request $request): Response
    {
        
        if (!$security->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_index');
        }
    
        $promotion = $promotionRepo->searchByName($request->query->get('libelle', ''));

        return $this->render('promotion/promotion_list.html.twig', [
            'title' => 'Liste des promotions',
            'promotion' => $promotion,
            'libelle' => $request->query->get('libelle', ''),
        ]);
    }

    #[Route('new_promotion', name: 'app_new_promotion')]
    public function new(Request $request,
     EntityManagerInterface $em,
     Security $security,
    ): Response
    {
        if (!$security->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_index');
        }
        $promotion = new Promotion();
        $form = $this->createForm(PromotionFormType::class, $promotion);

        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $em->persist($promotion);
            $em->flush();
            return $this->redirectToRoute('app_promotion_list');
    
        }
        return $this->render('promotion/promotion_new.html.twig', [
            'title' => 'Création d\'une nouvelle promotion',
            'form' => $form->createView(),
        ]);
    }

    #[Route('update_promotion/{id}', name: 'app_update_promotion')]
    public function update(
        Request $request,
        EntityManagerInterface $em,
        ?Promotion $promotion,
        Security $security
    ) {
        if (!$security->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_index');
        }
        if ($promotion === null) {
            return $this->redirectToRoute('app_admin_dashboard');
        }

        $form = $this->createForm(PromotionFormType::class, $promotion);

        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $em->persist($promotion);
            $em->flush();
            return $this->redirectToRoute('app_promotion_list');
        }
        return $this->render('promotion/promotion_new.html.twig', [
            'title' => 'Mise à jour d\'une promotion',
            'form' => $form,
        ]);
    }

    
    #[Route('delete_promotion/{id}', name: 'app_delete_promotion', methods: ['POST'])]
    public function delete(Request $request,
     Promotion $promotion,
     Security $security,
      EntityManagerInterface $entityManager): Response
    {
        if (!$security->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_index');
        }
        if ($promotion === null) {
            return $this->redirectToRoute('app_admin_dashboard');
        }
        if ($this->isCsrfTokenValid('delete'.$promotion->getId(), $request->request->get('_token'))) {
            $entityManager->remove($promotion);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_promotion_list', [], Response::HTTP_SEE_OTHER);
    }
}
