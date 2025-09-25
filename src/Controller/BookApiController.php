<?php
// src/Controller/BookApiController.php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class BookApiController extends AbstractController
{
    #[Route('/api/book/search', name: 'search_book', methods: ['GET'])]
    public function searchBook(Request $request): Response
    {
        $searchTerm = $request->query->get('q', '');
        $filter = $request->query->get('filter', '');

        // For portfolio purposes, we mock book search results
        $books = [];
        if ($searchTerm) {
            $books = [
                [
                    'title' => 'Sample Book 1',
                    'authors' => ['Author A'],
                    'thumbnail' => null,
                    'description' => 'This is a sample description for Book 1.',
                ],
                [
                    'title' => 'Sample Book 2',
                    'authors' => ['Author B'],
                    'thumbnail' => null,
                    'description' => 'This is a sample description for Book 2.',
                ],
            ];
        }

        return $this->render('book_api/search.html.twig', [
            'books' => $books,
            'query' => $searchTerm,
            'filter' => $filter,
        ]);
    }

    #[Route('/api/book/autocomplete', name: 'book_autocomplete', methods: ['GET'])]
    public function autocomplete(Request $request): JsonResponse
    {
        $query = $request->query->get('q', '');
        
        // Normally this would call Google Books API using Guzzle
        // Here we just return mock suggestions for portfolio purposes
        $suggestions = [];
        if (strlen($query) >= 2) {
            $suggestions = [
                $query . ' Sample Title 1',
                $query . ' Sample Title 2',
                $query . ' Sample Title 3',
            ];
        }

        return $this->json($suggestions);
    }
}
