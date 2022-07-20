<?php

namespace App\Controller\front;

use App\Entity\Book;
use App\Form\BookType;
use App\Repository\AuthorRepository;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class FrontBookController extends AbstractController
{
    /**
     * @Route("/book/{id}", name="front_show_book")
     */
    public function showBook(BookRepository $bookRepository, $id)
    {
        $book = $bookRepository -> find($id);
        return $this ->render('front/show_book.html.twig',[
            'book' => $book
        ]);
    }




    /**
     * @Route("/books", name="front_books")
     */
    public function listBooks(BookRepository $bookRepository)
    {
        $books =$bookRepository -> findAll();
        return $this->render('front/list_books.html.twig',[
            'books'=>$books
        ]);
    }
}