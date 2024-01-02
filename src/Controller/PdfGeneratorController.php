<?php

namespace App\Controller;

use App\Repository\AdresseRepository;
use App\Repository\CommandeRepository;
use App\Repository\LigneDeCommandeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;

class PdfGeneratorController extends AbstractController
{
    #[Route('/pdf/{id}', name: 'app_pdf_generator')]
    public function index(
        $id,
        LigneDeCommandeRepository $ligneRepo,
        CommandeRepository $commandeRepo,
        AdresseRepository $adresseRepo
    ): Response {
        $dataProduit = $ligneRepo->findByIdCommande($id);
        $dataCommande = $commandeRepo->findById($id);

        foreach ($dataCommande as $dataC) {
            $adresseLivraison = $dataC->getEstLivre();
            $adresseFacturation = $dataC->getEstFacture();
            $html = $this->renderView('pdf_generator/index.html.twig', [
                'dataProduit' => $dataProduit,
                'dataCommande' => $dataCommande,
                'adresseLivraison' => $adresseLivraison,
                'adresseFacturation' => $adresseFacturation,
            ]);

            $options = new Options();
            $options->set('isRemoteEnabled', true);

            $dompdf = new Dompdf($options);
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            $pdfOutput = $dompdf->output();



            return new Response($pdfOutput, 200, [
                'Content-Type' => 'application/pdf',
            ]);
        }
    }
}
