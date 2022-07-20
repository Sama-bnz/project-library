<?php

namespace App\Controller\front;

use App\Entity\Author;
use App\Form\AuthorType;
use App\Repository\AuthorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class FrontAuthorController extends AbstractController
{
    /**
     * @Route ("/authors", name="front_authors")
     */

    public function listAuthors(AuthorRepository $authorRepository)
    {
        $authors = $authorRepository->findAll();

        return $this->render('front/list_authors.html.twig', [
            'authors' => $authors
        ]);
    }

    /**
     * @Route("/author/{id}", name="front_show_author")
     */
    public function showAuthor($id, AuthorRepository $authorRepository)
    {
        $author = $authorRepository->find($id);
        return $this->render('front/show_author.html.twig', [
            'author' => $author
        ]);
    }



}