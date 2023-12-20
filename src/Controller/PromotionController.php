<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Promotion;
use App\Form\PromotionType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
    #[ROUTE('/admin' )]
class PromotionController extends AbstractController
{
    
    #[Route('/promotion', name: 'promotion')]
    public function list(EntityManagerInterface $entityManager): Response {
    $promotions = $entityManager->getRepository(Promotion::class)->findAll();

    return $this->render('promotion/index.html.twig', [
        'promotions' => $promotions,
    ]);
}

    

    #[Route('/promotion/create', name: 'promotion_create')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response {
        $promotion = new Promotion();
        $form = $this->createForm(PromotionType::class, $promotion);
    
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($promotion);
            $entityManager->flush();
    
            return $this->redirectToRoute('promotion'); 
        }
    
        return $this->render('promotion/create.html.twig', [
            'form' => $form->createView(),
        ]);
}

    #[Route('/promotion/edit/{id}', name: 'promotion_edit')]
    public function edit(Request $request, EntityManagerInterface $entityManager, Promotion $promotion): Response {
    $form = $this->createForm(PromotionType::class, $promotion);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager->flush();
        return $this->redirectToRoute('promotion');
    }

    return $this->render('promotion/edit.html.twig', [
        'form' => $form->createView(),
    ]);
}

    #[Route('/promotion/delete/{id}', name: 'promotion_delete')]
    public function delete(Promotion $promotion, EntityManagerInterface $entityManager): Response {
    $entityManager->remove($promotion);
    $entityManager->flush();

    return $this->redirectToRoute('promotion');
}


}

