<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\BookType;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminBookController extends AbstractController
{
    /**
     * @Route("/admin/book/{id}", name="admin_book")
     */
    public function showBook(BookRepository $bookRepository, $id)
    {
        $book = $bookRepository -> find($id);
        return $this ->render('admin/show_book.html.twig',[
        'book' => $book
        ]);
    }




    /**
    * @Route("/admin/books", name="admin_books")
    */
        public function listBooks(BookRepository $bookRepository)
    {
    $books =$bookRepository -> findAll();
    return $this->render('admin/list_books.html.twig',[
        'books'=>$books
    ]);
    }



    /**
     * @Route("/admin/insert-book", name="admin_insert_book")
     */
    //L'entity manager traduit en requete SQL
    public function insertBook(EntityManagerInterface $entityManager, Request $request)
    {

        //je créé une instance de la classs book (classe d'entité (celle qui as permis de crée la table))
//        dans le but de créer un nouvel article de la BDD (table book)

        $book = new Book();

//        j'ai utilisé la ligne de cmd php bin/console make:form pour créer une classe symfony qui va contenir le "plan" de formulaire afin de créer les articles. C'est la classe ArticleType

        $form = $this->createForm(BookType::class, $book);

        //On donne à la variable qui contient le formulaire une instance de la classe Request pour que le formulaire puisse récuperer tout les données des inputs et faire les setters sur $article automatiquement.
        //Mon formulaire est maintenant capable de recuperer et stocker les infos
        $form->handleRequest($request);

        //Si le formulaire à été posté et que les données sont valide
        if ($form->isSubmitted() && $form->isValid()) {
            //On enregistre l'article dans la BDD
            $entityManager->persist($book);
            $entityManager->flush();

            $this->addFlash('success', 'Le livre as bien  été créer');
        }

        //j'affiche mon twig en lui passant une variable form qui contient la view du formulaire

        return $this->render("admin/insert_book.html.twig", [
            'form' => $form->createView()
        ]);
    }
}