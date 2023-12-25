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
   private function formAdresse(Adresse $adresse, AdresseRepository $adresseRepo, CodePostalRepository $codePostalRepo, Request $request, Users $users, VilleRepository $villeRepo, $isUpdate = false)
   {
       $message = '';



       if (isset($_POST['submitAdresse'])) {
           $adresse->setNumVoie($request->request->get('num_voie'));
           $adresse->setRue($request->request->get('rue'));
           $adresse->setComplement($request->request->get('complement'));
           $users = $this->getUser();
           $adresse->setUsers($users);
           $ville = $villeRepo->find($request->request->get('villeId'));
           $adresse->setVille($ville);
           $codePostalId = $codePostalRepo->find($request->request->get('selectedPostalCodesId'));
           $adresse->setCodePostal($codePostalId);

           $adresseRepo->save($adresse, true);

           if ($request->get('id')) {
            
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
           'users' => $users,


       ]);
   }

   //Page de création d'adresse
   #[Route('/commande/create_adresse', name: 'app_create_adresse_commande')]
   public function createAdresse(AdresseRepository $adresseRepo, CodePostalRepository $codePostalRepo, Request $request, VilleRepository $villeRepo): Response
   {
       $users = $this->getUser();
       $adresse = new Adresse();
       return $this->formAdresse($adresse, $adresseRepo, $codePostalRepo, $request, $users, $villeRepo, false);
   }

   //Page de modification d'adresse
   #[Route('/commande/update_adresse/{id}', name: 'app_update_adresse_commande')]
   public function updateAdresse(Adresse $adresse, AdresseRepository $adresseRepo, CodePostalRepository $codePostalRepo, Request $request, VilleRepository $villeRepo): Response
   {
       $users = $this->getUser();
       return $this->formAdresse($adresse, $adresseRepo, $codePostalRepo, $request, $users,  $villeRepo, true);
   }
}
