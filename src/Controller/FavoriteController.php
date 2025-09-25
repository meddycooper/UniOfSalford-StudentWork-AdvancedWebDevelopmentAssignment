<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/favorites', name: 'favorites_')]
class FavoriteController extends AbstractController
{
    #[Route('/add/{id}', name: 'add', methods: ['POST'])]
    public function add(int $id): Response
    {
        // Portfolio-safe: mock behavior instead of persisting to DB
        $this->addFlash('success', "Book ID {$id} added to your favorites (mock).");

        // Redirect to book page placeholder
        return $this->redirectToRoute('book_show', ['id' => $id]);
    }

    #[Route('/remove/{id}', name: 'remove', methods: ['POST'])]
    public function remove(int $id): Response
    {
        // Portfolio-safe: mock behavior instead of DB remove
        $this->addFlash('success', "Book ID {$id} removed from favorites (mock).");

        return $this->redirectToRoute('book_show', ['id' => $id]);
    }

    #[Route('/', name: 'list')]
    public function list(): Response
    {
        // Portfolio-safe: provide sample favorite books
        $favorites = [
            ['id' => 1, 'title' => 'Sample Favorite Book 1', 'author' => 'Author A'],
            ['id' => 2, 'title' => 'Sample Favorite Book 2', 'author' => 'Author B'],
        ];

        return $this->render('favorite/list.html.twig', [
            'favorites' => $favorites
        ]);
    }
}
