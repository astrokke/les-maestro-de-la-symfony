<?php

namespace App\Controller;

use App\Entity\Adresse;
use App\Entity\CodePostal;
use App\Entity\Commande;
use App\Entity\Region;
use App\Entity\Users;
use App\Form\AdresseFormType;
use App\Form\UserFormType;
use App\Repository\AdresseRepository;
use App\Repository\CodePostalRepository;
use App\Repository\DepartementRepository;
use App\Repository\RegionRepository;
use App\Repository\UsersRepository;
use App\Repository\VilleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class CommandeController extends AbstractController
{
    #[Route('/commande', name: 'app_commande')]
    public function indexCommande(): Response
    {
        return $this->render('commande/index.html.twig', [
            'controller_name' => 'CommandeController',
        ]);
    }

    //Affichage Formulaire pour l'entité Adresse
    private function formAdresseCommande(Adresse $adresse, AdresseRepository $adresseRepo, Request $request, Commande $commande, EntityManagerInterface $em,  VilleRepository $villeRepo, $isUpdate = false)
    {
        $message = '';

        if (isset($_POST['submitAdresse'])) {
            $adresse->setNumVoie($_POST['num_voie']);
            $adresse->setRue($_POST['rue']);
            $adresse->setComplement($_POST['complement']);
            if (isset($_POST['mainAdress'])) {
                $users = $this->getUser();
                $adresse->addUser($users);
                $adresse->addEstFacture($commande);
            }
            $adresse->addEstLivre($commande);
            $ville = $villeRepo->find($_POST['villeId']);
            $adresse->setVille($ville);
            // Persist both the Adresse and Commande entities
            $em->persist($adresse);
            $em->persist($commande);

            // Flush to persist and make the changes permanent
            $em->flush();
            if ($request->get('id')) {
                $message = 'L\'adresse a bien été modifiée';
                return $this->redirectToRoute('app_commande', [
                    'message' => '2'
                ]);
            } else {
                $message = 'L\'adresse a bien été créée';
                if ($this->getUser()) {
                    return $this->redirectToRoute('app_commande', [
                        'message' => '1'
                    ]);
                } else {
                    return $this->redirectToRoute('app_login');
                }
            }
        }
        return $this->render('commande/new.html.twig', [
            'title' => 'adresse',
            'message' => $message,
            'flag' => $isUpdate,
            'adresse' => $adresse

        ]);
    }

    //Page de création d'adresse
    #[Route('/commande/create_adresse', name: 'app_create_adresse_commande')]
    public function createAdresseCommande(AdresseRepository $adresseRepo, Request $request, EntityManagerInterface $em, VilleRepository $villeRepo): Response
    {

        $adresse = new Adresse();
        $commande = new Commande();
        return $this->formAdresseCommande($adresse, $adresseRepo, $request, $commande, $em, $villeRepo, false);
    }

    //Page de modification d'adresse
    #[Route('/commande/update_adresse/{id}', name: 'app_update_adresse_commande')]
    public function updateAdresseCommande(Adresse $adresse, AdresseRepository $adresseRepo, Request $request, VilleRepository $villeRepo): Response
    {

        return $this->formAdresseCommande($adresse, $adresseRepo, $request,  $villeRepo, true);
    }


    #[Route('/adresse/ajax/ville/{name}', name: 'ajax_ville')]
    public function index(VilleRepository $cityRepo, Request $request): Response
    {
        $string = $request->get('name');
        $cities = $cityRepo->searchByName($string);
        $json = [];
        foreach ($cities as $city) {
            /*$codePostaux = [];
            
            //dd($city->getCodePostal());
            foreach($city->getCodePostal() as $codePostal)
            {
                $codePostaux[] = $codePostal->getLibelle();
            }*/
            $json[] = [
                'id' => $city->getId(), 'ville' => $city->getNom(),
                'codeDepartement' => $city->getDepartement()->getNom(),
                'region' => $city->getDepartement()->getRegion()->getNom(),
                //'code_postal' => $codePostaux
            ];
        }

        return new JsonResponse($json, 200);
    }
}
