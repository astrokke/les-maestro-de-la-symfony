<?php

namespace App\Controller;

use App\Repository\CommandeRepository;
use App\Repository\LigneDeCommandeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Dompdf\Dompdf;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;

class PdfGeneratorController extends AbstractController
{
    #[Route('/pdf/{id}', name: 'app_pdf_generator')]
    public function index(
        $id,
        LigneDeCommandeRepository $ligneRepo,
        CommandeRepository $commandeRepo

    ): Response {
        // return $this->render('pdf_generator/index.html.twig', [
        //     'controller_name' => 'PdfGeneratorController',
        // ]);

        $dataProduit = $ligneRepo->findByIdCommande($id);
        $dataCommande = $commandeRepo->findById($id);

        var_dump($dataCommande);
        var_dump($dataProduit);
        $html =  $this->renderView('pdf_generator/index.html.twig', [

            'dataProduit' => $dataProduit,
            'dataCommande' => $dataCommande,


        ]);
        foreach ($dataCommande as $dataC) {

            $numCommande = $dataC->getId();
        }
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->render();
        $dompdf->stream("Commande_Maestro_de_la_Symfony" . $numCommande, [
            "Attachment" => false,
        ]);

        return new Response('', 200, [
            'Content-Type' => 'application/pdf',
        ]);
    }
}
