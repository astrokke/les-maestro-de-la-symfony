<?php

namespace App\Controller;

use App\Entity\Livraison;
use App\Repository\LivraisonRepository;
use App\Entity\Adresse;
use App\Entity\CodePostal;
use App\Entity\Commande;
use App\Entity\Region;
use App\Entity\Users;
use App\Form\AdresseFormType;
use App\Form\CommandeFormType;
use App\Form\UserFormType;
use App\Repository\AdresseRepository;
use App\Repository\CodePostalRepository;
use App\Repository\DepartementRepository;
use App\Repository\EtatRepository;
use App\Repository\PanierRepository;
use App\Repository\PhotosRepository;
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
    public function NewCommande(Security $security, Request $request, EntityManagerInterface $em, EtatRepository $etatRepo, PanierRepository $panierRepo, PhotosRepository $photoRepo, AdresseRepository $adresseRepo): Response
    {
        if (!$security->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('app_index');
        }
        $user = $security->getUser();
        $id = $user->getId();
        $commande = new Commande();
        $adressesUtilisateur = $adresseRepo->findBy(['users' => $user]);
        $form = $this->createForm(CommandeFormType::class, $commande, [
            'adressesUtilisateur' => $adressesUtilisateur,
        ]);

        $panier = $panierRepo->getLastPanier($id);

        $total = 0;
        foreach ($panier->getPanierProduits() as $lignePanier) {

            $produits[] = [
                'id' => $lignePanier->getId(),
                'produit' => $lignePanier->getProduit(),
                'qte' => $lignePanier->getQuantite(),
                'photo' => $photoRepo->searchOnePhotoByProduit($lignePanier->getProduit()->getId()),
                'prixTTC' => $lignePanier->getProduit()->getPrixHT() + ($lignePanier->getProduit()->getPrixHT() * $lignePanier->getProduit()->getTVA()->getTauxTva() / 100),
            ];
            $total += ($lignePanier->getProduit()->getPrixHT() + ($lignePanier->getProduit()->getPrixHT() * $lignePanier->getProduit()->getTVA()->getTauxTva() / 100)) * $lignePanier->getQuantite();
        }

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $data = $form->getData();
            $commande->setLivraison($data->getLivraison());
            $commande->setPaiement($data->getPaiement());
            $etatUnique = $etatRepo->find(['id' => 1]);
            $commande->setEstFacture($data->getEstFacture());
            $commande->setEstLivre($data->getEstLivre());
            $commande->setEtat($etatUnique);
            $commande->setUsers($security->getUser());
            $commande->setPanier($panier);
            $commande->setDateCommande(new \DateTimeImmutable());
            $commande->setPrixTtcCommande($total);
            // Sauvegardez la commande en base de données

            $em->persist($commande);

            $em->flush();
            return $this->redirectToRoute('app_index');
        }

        return $this->render('commande/index.html.twig', [
            'controller_name' => 'CommandeController',
            'form' => $form,
            'totalttc' => $total,
        ]);
    }

    //Affichage Formulaire pour l'entité Adresse
    private function formAdresseCommande(Adresse $adresse,  Request $request, EntityManagerInterface $em, Users $users,  VilleRepository $villeRepo, $isUpdate = false)
    {
        $message = '';

        if (isset($_POST['submitAdresse'])) {
            $adresse->setNumVoie($_POST['num_voie']);
            $adresse->setRue($_POST['rue']);
            $adresse->setComplement($_POST['complement']);
           
                $users = $this->getUser();
                $adresse->setUsers($users);
            
            $ville = $villeRepo->find($_POST['villeId']);
            $adresse->setVille($ville);

            $em->persist($adresse);

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
            'adresse' => $adresse,

        ]);
    }

    //Page de création d'adresse
    #[Route('/commande/create_adresse', name: 'app_create_adresse_commande')]
    public function createAdresseCommande(AdresseRepository $adresseRepo, Request $request, EntityManagerInterface $em, VilleRepository $villeRepo): Response
    {

        $adresse = new Adresse();
        $user = $this->getUser();
        return $this->formAdresseCommande($adresse, $request, $em, $user, $villeRepo, false);
    }

    //Page de modification d'adresse
    #[Route('/commande/update_adresse/{id}', name: 'app_update_adresse_commande')]
    public function updateAdresseCommande(Adresse $adresse, AdresseRepository $adresseRepo, Request $request, EntityManagerInterface $em, VilleRepository $villeRepo): Response
    {
        $user = $this->getUser();
        return $this->formAdresseCommande($adresse, $request, $em, $user,  $villeRepo, true);
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
