<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ReviewController extends AbstractController
{
    #[Route('/reviews', name: 'app_review_list')]
    public function listReviews(): Response
    {
        // Portfolio-safe: mock list of reviews
        $reviews = [
            ['id' => 1, 'rating' => 5, 'review_text' => 'Great book!'],
            ['id' => 2, 'rating' => 4, 'review_text' => 'Interesting read.'],
        ];
        return $this->render('review/list.html.twig', [
            'reviews' => $reviews,
        ]);
    }

    #[Route('/review/{id}', name: 'app_review_show')]
    public function showReview(int $id): Response
    {
        // Portfolio-safe: mock review
        $review = ['id' => $id, 'rating' => 5, 'review_text' => 'Sample review'];
        $comments = [
            ['author' => 'User1', 'text' => 'I agree!'],
        ];
        return $this->render('review/show.html.twig', [
            'review' => $review,
            'comments' => $comments,
        ]);
    }

    #[Route('/review/create/{bookId}', name: 'app_review_create')]
    public function createReview(Request $request, int $bookId): Response
    {
        // Portfolio-safe: simulate form handling
        if ($request->isMethod('POST')) {
            $this->addFlash('success', 'Review created (mock).');
            return $this->redirectToRoute('app_review_show', ['id' => 1]);
        }

        return $this->render('review/create.html.twig', [
            'form' => null, // mock form
            'book' => ['id' => $bookId, 'title' => 'Sample Book'],
        ]);
    }

    #[Route('/review/edit/{id}', name: 'app_review_edit')]
    public function editReview(Request $request, int $id): Response
    {
        // Portfolio-safe: simulate form editing
        if ($request->isMethod('POST')) {
            $this->addFlash('success', 'Review updated (mock).');
            return $this->redirectToRoute('app_review_show', ['id' => $id]);
        }

        return $this->render('review/edit.html.twig', [
            'form' => null,
        ]);
    }

    #[Route('/api/reviews', name: 'list_reviews', methods: ['GET'])]
    public function listReviewsApi(): JsonResponse
    {
        // Portfolio-safe: mock API response
        $reviews = [
            ['id' => 1, 'rating' => 5, 'review_text' => 'Great book!'],
            ['id' => 2, 'rating' => 4, 'review_text' => 'Interesting read.'],
        ];
        return $this->json($reviews);
    }

    #[Route('/api/reviews/{id}', name: 'get_single_review', methods: ['GET'])]
    public function getSingleReviewApi(int $id): JsonResponse
    {
        return $this->json([
            'id' => $id,
            'rating' => 5,
            'review_text' => 'Sample review text',
            'user' => 'mockuser',
            'book' => 'Sample Book',
        ]);
    }

    #[Route('/review/{reviewId}/comment', name: 'app_comment_create')]
    public function createComment(int $reviewId, Request $request): Response
    {
        // Portfolio-safe: simulate comment creation
        if ($request->isMethod('POST')) {
            $this->addFlash('success', 'Comment created (mock).');
            return $this->redirectToRoute('app_review_show', ['id' => $reviewId]);
        }

        return $this->render('comment/create.html.twig', [
            'form' => null,
            'review' => ['id' => $reviewId, 'review_text' => 'Sample review'],
        ]);
    }

    #[Route('/review/{id}/upvote', name: 'review_upvote', methods: ['POST'])]
    public function upvote(int $id): Response
    {
        // Portfolio-safe: simulate upvote
        $this->addFlash('success', 'Thanks for your upvote (mock).');
        return $this->redirectToRoute('app_review_show', ['id' => $id]);
    }
}
