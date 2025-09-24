<?php
// src/Controller/BookApiController.php

namespace App\Controller;

use GuzzleHttp\Client;
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
        if (!$searchTerm) {
            return $this->render('book_api/search.html.twig', [
                'books' => [],
                'query' => '',
                'filter' => $filter,
            ]);
        }
        $queryParam = $filter ? $filter . ':' . $searchTerm : $searchTerm;
        $client = new Client();
        $response = $client->request('GET', 'https://www.googleapis.com/books/v1/volumes', [
            'query' => [
                'q' => $queryParam,
                'key' => 'my key',
                'maxResults' => 10,
            ]
        ]);
        $data = json_decode($response->getBody(), true);
        $books = [];
        if (isset($data['items'])) {
            foreach ($data['items'] as $item) {
                $volumeInfo = $item['volumeInfo'];
                $books[] = [
                    'title' => $volumeInfo['title'] ?? 'No title',
                    'authors' => $volumeInfo['authors'] ?? [],
                    'thumbnail' => $volumeInfo['imageLinks']['thumbnail'] ?? null,
                    'description' => $volumeInfo['description'] ?? '',
                ];
            }
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
        if (strlen($query) < 2) {
            return $this->json([]);
        }
        $client = new Client();
        $response = $client->request('GET', 'https://www.googleapis.com/books/v1/volumes', [
            'query' => [
                'q' => 'intitle:' . $query,
                'key' => 'AIzaSyADCFpacAi2WExIWu1QpZfkaZcXp5P869Q',
                'maxResults' => 5,
            ]
        ]);
        $data = json_decode($response->getBody(), true);
        $suggestions = [];
        if (!empty($data['items'])) {
            foreach ($data['items'] as $item) {
                $title = $item['volumeInfo']['title'] ?? '';
                if ($title) {
                    $suggestions[] = $title;
                }
            }
        }
        return $this->json($suggestions);
    }
}
