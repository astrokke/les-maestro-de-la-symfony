<?php

namespace App\Controller;

use App\Entity\Adresse;
use App\Entity\CodePostal;
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

class UserPanelController extends AbstractController
{
    #[Route('/user', name: 'app_user')]
    public function indexAccount(Security $security, ?Adresse $adresse): Response
    {
        $user = $security->getUser();
        return $this->render('user/index.html.twig', [
            'title' => 'Vos informations',
            'users' => $user,
            'adresse' => $adresse,
        ]);
    }

    #[Route('/user/information/{id}', name: 'app_user_account')]
    public function userAccount(?Users $users, Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $em): Response
    {
        if ($users === null) {
            return $this->redirectToRoute('app_index');
        }

        $form = $this->createForm(UserFormType::class, $users);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $users->setPassword(
                $userPasswordHasher->hashPassword(
                    $users,
                    $form->get('newPassword')->getData()
                )
            );
            $em->persist($users);
            $em->flush();
            return $this->redirectToRoute('app_user');
        }
        return $this->render('user/updateAccount.html.twig', [
            'title' => 'Vos informations' . ' ' . $users->getPrenom(),
            'users' => $users,
            'form' => $form,
        ]);
    }
    #[Route('/user/list_adresse', name: 'app_list_adresse')]
    public function list(AdresseRepository $adresseRepo, Request $request): Response
    {
        $triRue = $request->query->get('trirue', 'asc');
        $adresses = $adresseRepo->searchByName($request->query->get('rue', ''), $triRue);

        return $this->render('user/list.html.twig', [
            'title' => 'Liste de vos adresses',
            'adresses' => $adresses,
            'trirue' => $triRue,
            'rue' => $request->query->get('rue', ''),
        ]);
    }
    #[Route('/user/adresse/{id}', name: 'app_show_adresse')]
    public function showAdresse(?Adresse $adresse)
    {
        if ($adresse === null) {
            return $this->redirectToRoute('app_create_adresse');
        }
        $user = $this->getUser();

        return $this->render('user/showAdresse.html.twig', [
            'title' => 'Information de l\'adresse ',
            'adresse' => $adresse,
            'user' => $user

        ]);
    }

    //Affichage Formulaire pour l'entité Adresse
    private function formAdresse( Adresse $adresse, AdresseRepository $adresseRepo, Request $request, Users $users, VilleRepository $villeRepo, $isUpdate = false)
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
            $adresseRepo->save($adresse, true);

            if ($request->get('id')) {
                $message = 'L\'adresse a bien été modifiée';
                return $this->redirectToRoute('app_user', [
                    'message' => '2'
                ]);
            } else {
                $message = 'L\'adresse a bien été créée';
                if ($this->getUser()) {
                    return $this->redirectToRoute('app_user', [
                        'message' => '1'
                    ]);
                } else {
                    return $this->redirectToRoute('app_login');
                }
            }
        }
        return $this->render('user/new.html.twig', [
            'title' => 'adresse',
            'message' => $message,
            'flag' => $isUpdate,
            'adresse' => $adresse,
            'users' => $users

        ]);
    }

    //Page de création d'adresse
    #[Route('/user/create_adresse', name: 'app_create_adresse')]
    public function createAdresse(AdresseRepository $adresseRepo, Request $request, VilleRepository $villeRepo): Response
    {
        $users = $this->getUser();
        $adresse = new Adresse();
        return $this->formAdresse($adresse, $adresseRepo, $request, $users, $villeRepo, false);
    }

    //Page de modification d'adresse
    #[Route('/user/update_adresse/{id}', name: 'app_update_adresse')]
    public function updateAdresse(Adresse $adresse, AdresseRepository $adresseRepo, Request $request, VilleRepository $villeRepo): Response
    {
        $users = $this->getUser();
        return $this->formAdresse($adresse, $adresseRepo, $request, $users,  $villeRepo, true);
    }


    #[Route('/adresse/ajax/ville/{name}', name: 'ajax_ville')]
    public function index(VilleRepository $cityRepo, Request $request): Response
    {
        $string = $request->get('name');
        $cities = $cityRepo->searchByName($string);
        $json = [];
        foreach ($cities as $city) {
            $codePostaux = [];
            
            
            foreach($city->getCodePostal() as $codePostal)
            {
                $codePostaux[] = $codePostal->getLibelle();
            }
            $json[] = [
                'id' => $city->getId(), 
                'ville' => $city->getNom(),
                'codeDepartement' => $city->getDepartement()->getNom(),
                'region' => $city->getDepartement()->getRegion()->getNom(),
                'codePostaux' => $codePostaux,
            ];
        }

        return new JsonResponse($json, 200);
    }
}
