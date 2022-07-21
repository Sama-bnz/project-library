<?php

namespace App\Controller\admin;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;


class AdminAdminController extends AbstractController
{
    /**
     * @Route("/admin/admins", name="admin_list_admins")
     */
    public function listAdmin(UserRepository $userRepository)
    {
        $admins = $userRepository->findAll();

        return $this -> render('admin/admins.html.twig',[
            'admins'=>$admins
        ]);
    }

    /**
     * @Route("/admin/create", name="admin_create_admin")
     */
    public function createAdmin(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $userPasswordHasher)
    {
        //je créé une nouvelle instance de la classe admin
        $user= new User();

        // j'affecte le rôle d'admin par défaut
        $user->setRoles(["ROLE_ADMIN"]);

        //J'appel mon gabari de formulaire à l'aide de createform
        $form=$this->createForm(UserType::class,$user);

        //j'utile l'instance de classe request pour récupérer la valeur de mon formulaire
        $form->handleRequest($request);

        //si le formulaire est soumis et valide
        if($form->isSubmitted() && $form->isValid()){

            //je récupère le mot de passe depuis le formulaire
            $plainPassword= $form->get('password')->getData();

            //Je veux crypter mon mot de passe à l'aide de la fonction Hash
            $hashedPassword = $userPasswordHasher->hashPassword($user,$plainPassword);

            $user->setPassword($hashedPassword);

            //J'envoie les données du formulaire à la BDD
            $entityManager->persist($user);
            $entityManager->flush();

            //Petit message comme quoi la demande à bien été executée
            $this->addFlash('success', 'Votre admin as été créer!');

            //Je redirige vers la route list admin
            return $this->redirectToRoute("admin_list_admins");

        }

        //Je renvoi vers mon formulaire de creation d'admin
        return $this->render('admin/insert_admin.html.twig',[
            'form'=>$form->createView()
        ]);
    }
}