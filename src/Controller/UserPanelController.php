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
   
}
