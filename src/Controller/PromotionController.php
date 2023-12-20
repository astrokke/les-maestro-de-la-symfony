<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

use App\Entity\Promotion;
use App\Form\PromotionType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;

class PromotionController extends AbstractController
{
    #[Route('/promotion', name: 'promotion')]
    public function listAndCreate(Request $request, EntityManagerInterface $entityManager): Response {
        $promotion = new Promotion();
        $form = $this->createForm(PromotionType::class, $promotion);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($promotion);
            $entityManager->flush();

            return $this->redirectToRoute('promotion');
        }

        
        $promotions = $entityManager->getRepository(Promotion::class)->findAll();

        return $this->render('promotion/index.html.twig', [
            'form' => $form->createView(),
            'promotions' => $promotions,
        ]);
    }

    

    // Route pour supprimer une promotion
    #[Route('/promotion/delete/{id}', name: 'promotion_delete', methods: ["POST"])]
    public function delete(Promotion $promotion): Response {
        
        return $this->redirectToRoute('promotion'); 
    }

  
}

