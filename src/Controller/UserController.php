<?php

namespace App\Controller;
use App\Entity\Users;

use App\Repository\UsersRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    // #[Route('/user', name: 'app_user')]
    // public function index(): Response
    // {
    //     return $this->render('user/index.html.twig', [
    //         'controller_name' => 'UserController',
    //     ]);
    // }

    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UsersRepository $userRepository, UserPasswordHasherInterface $passwordHasher): Response
    {
        $message = '';
        if ($request->getMethod() === "POST") {
            $nom = $request->request->get('nom');
            $prenom = $request->request->get('prenom');
            $email = $request->request->get('email');
            $role = $request->request->get('role');
            $password = $request->request->get('password');
            $adress = $request->request->get('adress');

            if ($nom === "" || $prenom=== "" || $email === "" || $role === "" || $password === "" || $adress === "") {
                $message = "les champs ne doivent pas être vide";
            } else {
                $user = new Users();
                $user->setNom($nom);
                $user->setPrenom($prenom);
                $user->setEmail ($email);
                $user->setRole($role);
                $hashedPassword = $passwordHasher->hashPassword($user, $password);
                $user->setPassword($hashedPassword);
                $userRepository->save($user);
                $message = "Nouvel utilisateur crée";
            }
        }
        return $this->render('user/index.html.twig', [
            'message' => $message
        ]);
    }


}
