<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\AuthorType;
use App\Form\BookType;
use App\Repository\BookRepository;
use App\Repository\AuthorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class AdminBookController extends AbstractController
{
    /**
     * @Route("/admin/book/{id}", name="admin_show_book")
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
    public function insertBook(EntityManagerInterface $entityManager, Request $request, SluggerInterface $slugger)
    {

        //je créé une instance de la classe book (classe d'entité (celle qui as permis de crée la table))
//        dans le but de créer un nouvel article de la BDD (table book)

        $book = new Book();

// j'ai utilisé la ligne de cmd php bin/console make:form pour créer une classe symfony qui va contenir le "plan" de formulaire afin de créer les articles. C'est la classe ArticleType

        $form = $this->createForm(BookType::class, $book);

        //On donne à la variable qui contient le formulaire une instance de la classe Request pour que le formulaire puisse récuperer tout les données des inputs et faire les setters sur $article automatiquement.
        //Mon formulaire est maintenant capable de recuperer et stocker les infos
        $form->handleRequest($request);

        //Si le formulaire à été posté et que les données sont valide
        if ($form->isSubmitted() && $form->isValid()) {

            $image = $form->get('image')->getData();
            //Je récupere le nom du fichier original
            $originalFilename = pathinfo($image->getClientOriginalName(),PATHINFO_FILENAME);

            //J'utilise une instance de la classe slugger et sa méthode slug pour supprimer
            //les caracteres spéciaux
            $safeFilename = $slugger->slug($originalFilename);
            //Je rajoute au nom de l'image un identifiant unique
            $newFileName = $safeFilename.'-'.uniqid().'.'. $image->guessExtension();

            //Je déplace l'image dans le dossier public et je la renomme avec le nouveau nom crée
            $image->move(
                $this->getParameter('images_directory'),
                $newFileName
            );
            $book->setImage($newFileName);

            //On enregistre le book dans la BDD
            $entityManager->persist($book);
            $entityManager->flush();

            $this->addFlash('success', 'Le livre as bien  été créer');
        }

        //j'affiche mon twig en lui passant une variable form qui contient la view du formulaire

        return $this->render("admin/insert_book.html.twig", [
            'form' => $form->createView()
        ]);
    }



    //On supprime un auteur à l'aide de son id
    //Mélange de ArticleRepository pour le sélectionner puis EntityManager pour le supprimer.
    /**
     * @Route ("/admin/book/delete/{id}", name="admin_delete_book")
     */
    public function deleteBook(AuthorRepository $bookRepository, $id, EntityManagerInterface $entityManager)
    {
        $book = $bookRepository->find($id);

        if (!is_null($book)) {
            $entityManager->remove($book);
            $entityManager->flush();
            $this->addFlash('success', "Votre livre as bien été supprimé");
            return $this->redirectToRoute('admin_books');

        } else {
            $this->addFlash('success', "Le livre as déja été supprimé");
            return $this->redirectToRoute('admin_books');
        }
    }



    /**
     * @Route("/admin/book/update/{id}", name="admin_update_book")
     */
    public function updateBook($id, BookRepository $bookRepository, EntityManagerInterface $entityManager, Request $request, SluggerInterface $slugger)
    {
        //Avec le repository je selectionne un book en fonction de l'ID
        $book = $bookRepository->find($id);

//        j'ai utilisé la ligne de cmd php bin/console make:form pour créer une classe symfony qui va contenir le "plan" de formulaire afin de créer les articles. C'est la classe BookType

        $form = $this->createForm(BookType::class, $book);

        //On donne à la variable qui contient le formulaire une instance de la classe Request pour que le formulaire puisse récuperer tout les données des inputs et faire les setters sur $article automatiquement.
        //Mon formulaire est maintenant capable de recuperer et stocker les infos
        $form->handleRequest($request);

        //Si le formulaire à été posté et que les données sont valide
        if ($form->isSubmitted() && $form->isValid()) {

            $image = $form->get('image')->getData();
            //Je récupere le nom du fichier original
            $originalFilename = pathinfo($image->getClientOriginalName(),PATHINFO_FILENAME);

            //J'utilise une instance de la classe slugger et sa méthode slug pour supprimer
            //les caracteres spéciaux
            $safeFilename = $slugger->slug($originalFilename);
            //Je rajoute au nom de l'image un identifiant unique
            $newFileName = $safeFilename.'-'.uniqid().'.'. $image->guessExtension();

            //Je déplace l'image dans le dossier public et je la renomme avec le nouveau nom crée
            $image->move(
                $this->getParameter('images_directory'),
                $newFileName
            );
            $book->setImage($newFileName);
            //On enregistre le book dans la BDD
            $entityManager->persist($book);
            $entityManager->flush();

            $this->addFlash('success', 'Le livre as bien été modifié!');
        }

        //j'affiche mon twig en lui passant une variable form qui contient la view du formulaire

        return $this->render("admin/update_book.html.twig", [
            'form' => $form->createView(),
            'book' => $book
        ]);
    }




    /**
     * Je créer une route search avec une fonction qui permettra de rechercher un book présent dans la liste des books
     * @Route("/admin/search/books", name="admin_search_books")
     */
    public function searchBooks(Request $request, BookRepository $bookRepository)
    {
        //Je récupère la valeur du formulaire avec ma nouvelle variable $search
        $search = $request->query->get('search');
        //Je vais créer une méthode dans BookRepository qui va permettre de trouver un article en fonction de son titre ou de son contenu
        $books = $bookRepository->searchByWord($search);

        //Je renvoie vers le fichier twig afin d'afficher les articles trouvés
        return $this->render('admin/search_books.html.twig',[
            'books' => $books
        ]);



    }
}