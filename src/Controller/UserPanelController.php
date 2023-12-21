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
    public function indexAccount(Security $security): Response
    {
        $user = $security->getUser();
        return $this->render('user/index.html.twig', [
            'title' => 'Vos informations',
            'users' => $user,
        ]);
    }

    #[Route('/user/information/{id}', name: 'app_user_account')]
    public function userAccount(?Users $users, Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $em, Security $security): Response
    {
        if ($users === null) {
            return $this->redirectToRoute('app_admin_index');
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

    //Affichage Formulaire pour l'entité Adresse
    private function formAdresse(
        Adresse $adresse,
        AdresseRepository $adresseRepo,
        Request $request,
        RegionRepository $regionRepo,
    ) {
        $message = '';
        if (isset($_POST['submitAdresse'])) {

            $ville = $regionRepo->find($_POST['regionId']);
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
            'title' => 'Creation d\'une adresse de livraison',
            'message' => $message,
        ]);
    }

    //Page de création d'adresse
    #[Route('/create_adresse', name: 'createAdresse')]
    public function createAdresse(AdresseRepository $adresseRepo, Request $request,  RegionRepository $regionRepo): Response
    {

        $adresse = new Adresse();
        return $this->formAdresse($adresse, $adresseRepo, $request, $regionRepo);
    }

    #[Route('user/newadress/region/{name}', name: 'app_ajax_adress')]
    public function newAdressAcount(RegionRepository $regionRepo, Request $request): Response
    {
        $string = $request->get('name');
        $regions = $regionRepo->searchByName($string);

        $json = [];

        foreach ($regions as $region) {
            $json[] = ['id ' => $region->getId(), 'region' => $region->getNom()];
        }

        return new JsonResponse($json, 200);
    }
    /*#[Route('user/newadress/region/{id}/departements', name: 'app_ajax_departements')]
    public function DepartementsParRegion(DepartementRepository $departmentRepo, ?Region $region): JsonResponse
    {
        $regionId = $region->getId();
        $departements = $departmentRepo->findByRegionId($regionId);

        $json = [];

        foreach ($departements as $departement) {
            $json[] = [
                'id' => $departement->getId(), 'departement' => $departement->getNom(),
                'region_id' => $departement->getRegion()->getNom()
            ];
        }

        return new JsonResponse($json, 200);
    }*/


    #[Route('/adresse/ajax/ville/{name}', name: 'ajax_ville')]
    public function index(VilleRepository $cityRepo, Request $request): Response
    {
        $string = $request->get('name');
        $cities = $cityRepo->searchByName($string);
        $json = [];
        dd($cities);
        foreach ($cities as $city) {
            $codePostaux = [];
            
            //dd($city->getCodePostal());
            foreach($city->getCodePostal() as $codePostal)
            {
                $codePostaux[] = $codePostal->getLibelle();
            }
            $json[] = [
                'id' => $city->getId(), 'ville' => $city->getNom(),
                'codeDepartement' => $city->getDepartement()->getNumeroDepartement(),
                'region' => $city->getDepartement()->getRegion()->getNom(),
                'code_postal' => $codePostaux
            ];
        }

        return new JsonResponse($json, 200);
    }
}
