<?php

namespace App\Controller\admin;

use App\Entity\Author;
use App\Form\AuthorType;
use App\Repository\AuthorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdminAuthorController extends AbstractController
{
    /**
     * @Route ("/admin/authors", name="admin_authors")
     */

    public function listAuthors(AuthorRepository $authorRepository)
    {
        $authors = $authorRepository->findAll();

        return $this->render('admin/list_authors.html.twig', [
            'authors' => $authors
        ]);
    }

    /**
     * @Route("/admin/author/{id}", name="admin_show_author")
     */
    public function showAuthor($id, AuthorRepository $authorRepository)
    {
        $author = $authorRepository->find($id);
        return $this->render('admin/show_author.html.twig', [
            'author' => $author
        ]);
    }


    /**
     * @Route("/admin/insert-author", name="admin_insert_author")
     */
    //L'entity manager traduit en requete SQL
    public function insertAuthor(EntityManagerInterface $entityManager, Request $request)
    {

        //je créé une instance de la classs article (classe d'entité (celle qui as permis de crée la table))
//        dans le but de créer un nouvel article de la BDD (table article)

        $author = new Author();

//        j'ai utilisé la ligne de cmd php bin/console make:form pour créer une classe symfony qui va contenir le "plan" de formulaire afin de créer les articles. C'est la classe ArticleType

        $form = $this->createForm(AuthorType::class, $author);

        //On donne à la variable qui contient le formulaire une instance de la classe Request pour que le formulaire puisse récuperer tout les données des inputs et faire les setters sur $article automatiquement.
        //Mon formulaire est maintenant capable de recuperer et stocker les infos
        $form->handleRequest($request);

        //Si le formulaire à été posté et que les données sont valide
        if ($form->isSubmitted() && $form->isValid()) {
            //On enregistre l'article dans la BDD
            $entityManager->persist($author);
            $entityManager->flush();

            $this->addFlash('success', 'L\'auteur as bien été créer');
        }

        //j'affiche mon twig en lui passant une variable form qui contient la view du formulaire

        return $this->render("admin/insert_author.html.twig", [
            'form' => $form->createView()
        ]);
    }




    //On supprime un auteur à l'aide de son id
    //Mélange de ArticleRepository pour le sélectionner puis EntityManager pour le supprimer.
    /**
     * @Route ("/admin/author/delete/{id}", name="admin_delete_author")
     */
    public function deleteAuthor(AuthorRepository $authorRepository, $id, EntityManagerInterface $entityManager)
    {
        $author = $authorRepository->find($id);

        if (!is_null($author)) {
            $entityManager->remove($author);
            $entityManager->flush();
            $this->addFlash('success', "Votre auteur as bien été supprimé");
            return $this->redirectToRoute('admin_authors');

        } else {
            $this->addFlash('success', "L'auteur as déja été supprimé");
            return $this->redirectToRoute('admin_authors');
        }
    }


    /**
     * @Route("/admin/author/update/{id}", name="admin_update_author")
     */
    public function updateAuthor($id, AuthorRepository $authorRepository, EntityManagerInterface $entityManager, Request $request)
    {
        //Avec le repository je selectionne un article en fonction de l'ID
        $author = $authorRepository->find($id);

//        j'ai utilisé la ligne de cmd php bin/console make:form pour créer une classe symfony qui va contenir le "plan" de formulaire afin de créer les articles. C'est la classe ArticleType

        $form = $this->createForm(AuthorType::class, $author);

        //On donne à la variable qui contient le formulaire une instance de la classe Request pour que le formulaire puisse récuperer tout les données des inputs et faire les setters sur $article automatiquement.
        //Mon formulaire est maintenant capable de recuperer et stocker les infos
        $form->handleRequest($request);

        //Si le formulaire à été posté et que les données sont valide
        if ($form->isSubmitted() && $form->isValid()) {
            //On enregistre l'article dans la BDD
            $entityManager->persist($author);
            $entityManager->flush();

            $this->addFlash('success', 'L\'article est bien enregistré!');
        }

        //j'affiche mon twig en lui passant une variable form qui contient la view du formulaire

        return $this->render("admin/insert_author.html.twig", [
            'form' => $form->createView()
        ]);
    }
}