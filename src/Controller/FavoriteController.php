<?php

namespace App\Controller;

use App\Entity\Book;
use App\Entity\Favorite;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;


#[Route('/favorites', name: 'favorites_')]
class FavoriteController extends AbstractController
{
    #[Route('/add/{id}', name: 'add', methods: ['POST'])]
    public function add(Book $book, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();

        // Check if already favorited
        $existing = $em->getRepository(Favorite::class)->findOneBy(['user' => $user, 'book' => $book]);
        if ($existing) {
            $this->addFlash('info', 'Book is already in your favorites.');
            return $this->redirectToRoute('book_show', ['id' => $book->getId()]);
        }

        $favorite = new Favorite();
        $favorite->setUser($user);
        $favorite->setBook($book);

        $em->persist($favorite);
        $em->flush();

        $this->addFlash('success', 'Book added to your favorites.');
        return $this->redirectToRoute('book_show', ['id' => $book->getId()]);
    }

    #[Route('/remove/{id}', name: 'remove', methods: ['POST'])]
    public function remove(Book $book, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();

        $favorite = $em->getRepository(Favorite::class)->findOneBy(['user' => $user, 'book' => $book]);
        if ($favorite) {
            $em->remove($favorite);
            $em->flush();
            $this->addFlash('success', 'Book removed from favorites.');
        }

        return $this->redirectToRoute('book_show', ['id' => $book->getId()]);
    }

    #[Route('/', name: 'list')]
    public function list(): Response
    {
        $favorites = $this->getUser()->getFavorites();
        return $this->render('favorite/list.html.twig', ['favorites' => $favorites]);
    }
}
