<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;

class BookController extends AbstractController
{
    #[Route('/book', name: 'app_book')]
    public function index(): Response
    {
        // Portfolio-safe: mock books instead of querying database
        $books = [
            ['id' => 1, 'title' => 'Sample Book 1', 'author' => 'Author A'],
            ['id' => 2, 'title' => 'Sample Book 2', 'author' => 'Author B'],
        ];

        return $this->render('book/list.html.twig', [
            'books' => $books
        ]);
    }

    #[Route('/book/new', name: 'book_new')]
    public function new(Request $request): Response
    {
        // Portfolio-safe: demonstrate form usage without DB or file uploads
        // $form = $this->createForm(BookType::class);
        // $form->handleRequest($request);

        // Normally here you would persist the new book to the database

        return $this->render('book/new.html.twig', [
            'form' => 'Form placeholder for portfolio', // instead of $form->createView()
        ]);
    }

    #[Route('/book/{id<\d+>}', name: 'book_show')]
    public function show(int $id): Response
    {
        // Portfolio-safe: mock a single book with reviews
        $book = ['id' => $id, 'title' => 'Sample Book ' . $id, 'author' => 'Author X'];
        $reviews = [
            ['id' => 1, 'rating' => 5, 'review_text' => 'Excellent book!', 'user' => 'user1', 'created_at' => '2025-01-01'],
            ['id' => 2, 'rating' => 4, 'review_text' => 'Very good', 'user' => 'user2', 'created_at' => '2025-02-01'],
        ];

        return $this->render('book/show.html.twig', [
            'book' => $book,
            'reviews' => $reviews,
            'commentsByReview' => [], // skip comments for portfolio
            'isFavorite' => false,
        ]);
    }

    #[Route('/book/search', name: 'book_search')]
    public function search(Request $request): Response
    {
        // Portfolio-safe: simulate search results
        $query = $request->query->get('q', '');
        $books = $query ? [
            ['id' => 1, 'title' => $query . ' Book 1', 'author' => 'Author A'],
            ['id' => 2, 'title' => $query . ' Book 2', 'author' => 'Author B'],
        ] : [];

        return $this->render('book/search.html.twig', [
            'form' => 'Search form placeholder', // instead of real form
            'books' => $books,
        ]);
    }

    #[Route('/api/books/{bookId}/reviews', name: 'api_book_reviews', methods: ['GET'])]
    public function getReviewsByBook(int $bookId): JsonResponse
    {
        // Portfolio-safe: mock review data
        $data = [
            ['id' => 1, 'rating' => 5, 'review_text' => 'Excellent book!', 'user' => 'user1', 'created_at' => '2025-01-01 12:00:00'],
            ['id' => 2, 'rating' => 4, 'review_text' => 'Very good', 'user' => 'user2', 'created_at' => '2025-02-01 09:30:00'],
        ];

        return $this->json($data);
    }
}
