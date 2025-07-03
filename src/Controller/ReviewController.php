<?php

namespace App\Controller;

use App\Entity\Review;
use App\Entity\ReviewVote;
use App\Form\ReviewType;
use App\Repository\ReviewRepository;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Security;
use App\Entity\Comment;
use App\Form\CommentType;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\HttpFoundation\RedirectResponse;

class ReviewController extends AbstractController
{
    // Display list of reviews (for authenticated users)
    #[Route('/reviews', name: 'app_review_list')]
    public function listReviews(ReviewRepository $reviewRepository): Response
    {
        $reviews = $reviewRepository->findAll();
        return $this->render('review/list.html.twig', [
            'reviews' => $reviews,
        ]);
    }

    // Show a single review
    #[Route('/review/{id}', name: 'app_review_show')]
    public function showReview(Review $review): Response
    {
        // Assuming $review->getComments() returns a collection of comments
        $comments = $review->getComments();

        return $this->render('review/show.html.twig', [
            'review' => $review,
            'comments' => $comments,
        ]);
    }

// ReviewController.php

    #[Route('/review/create/{bookId}', name: 'app_review_create')]
    public function createReview(Request $request, EntityManagerInterface $entityManager, BookRepository $bookRepository, int $bookId): Response
    {
        $book = $bookRepository->find($bookId);

        if (!$book) {
            throw $this->createNotFoundException('The book does not exist.');
        }

        $review = new Review();
        $form = $this->createForm(ReviewType::class, $review);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Automatically set the logged-in user
            $review->setUser($this->getUser());
            // Set the associated book
            $review->setBook($book);
            // Set the current time as creation date
            $review->setCreatedAt(new \DateTime());

            // Persist the new review to the database
            $entityManager->persist($review);
            $entityManager->flush();

            return $this->redirectToRoute('app_review_show', ['id' => $review->getId()]);
        }

        return $this->render('review/create.html.twig', [
            'form' => $form->createView(),
            'book' => $book,
        ]);
    }

    // Edit a review (for authenticated users)
    #[Route('/review/edit/{id}', name: 'app_review_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Review $review, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        if (!$user || $review->getUser() !== $user) {
            throw $this->createAccessDeniedException('You cannot edit this review.');
        }

        $form = $this->createForm(ReviewType::class, $review);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Review updated successfully.');
            return $this->redirectToRoute('user_reviews');
        }

        return $this->render('review/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    #[Route('/review/delete/{id}', name: 'app_review_delete', methods: ['POST', 'DELETE'])]
    public function deleteReview(Request $request, Review $review, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        if (!$user || $review->getUser() !== $user) {
            throw $this->createAccessDeniedException('You cannot delete this review.');
        }

        if ($this->isCsrfTokenValid('delete' . $review->getId(), $request->request->get('_token'))) {
            $entityManager->remove($review);
            $entityManager->flush();

            $this->addFlash('success', 'Review deleted successfully.');
        }

        return $this->redirectToRoute('user_reviews');
    }
    //api section
    // List all reviews
    #[Route('/api/me', name: 'api_me', methods: ['GET'])]
    public function getCurrentUser(Security $security): JsonResponse
    {
        $user = $security->getUser();
        return $this->json([
            'email' => $user->getUserIdentifier(),
            'roles' => $user->getRoles(),
        ]);
    }
    #[Route('/api/reviews', name: 'list_reviews', methods: ['GET'])]
    public function listReviewsApi(ReviewRepository $reviewRepository): JsonResponse
    {
        $reviews = $reviewRepository->findAll();

        $data = array_map(function (Review $review) {
            return [
                'id' => $review->getId(),
                //'content' => $review->getContent(),
                'rating' => $review->getRating(),
                'links' => [
                    'self' => $this->generateUrl('update_review', ['id' => $review->getId()]),
                    'delete' => $this->generateUrl('delete_review', ['id' => $review->getId()])
                ]
            ];
        }, $reviews);
        return $this->json($data);
    }

    // Create a new review
    #[Route('/api/reviews', name: 'create_review_api', methods: ['POST'])]
    public function createReviewApi(Request $request, BookRepository $bookRepo, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $book = $bookRepo->find($data['book_id']);

        if (!$book) {
            return $this->json(['message' => 'Book not found'], JsonResponse::HTTP_BAD_REQUEST);
        }

        $review = new Review();
        $review->setRating($data['rating']);
        $review->setReviewText($data['review_text'] ?? '');
        $review->setCreatedAt(new \DateTime());
        $review->setBook($book);
        $review->setUser($this->getUser());

        $em->persist($review);
        $em->flush();

        return $this->json(['message' => 'Review created', 'id' => $review->getId()], JsonResponse::HTTP_CREATED);
    }

    // Update a review
    #[Route('/api/reviews/{id}', name: 'update_review', methods: ['PUT'])]
    public function updateReview(
        $id,
        Request $request,
        ReviewRepository $reviewRepository,
        EntityManagerInterface $em
    ): JsonResponse
    {
        $review = $reviewRepository->find($id);
        if (!$review) {
            return $this->json(['message' => 'Review not found'], JsonResponse::HTTP_NOT_FOUND);
        }
        $data = json_decode($request->getContent(), true);
        $review->setReviewText($data['review_text'] ?? $review->getReviewText());
        $review->setRating($data['rating'] ?? $review->getRating());
        $em->flush();
        return $this->json([
            'id' => $review->getId(),
            'rating' => $review->getRating(),
            'review_text' => $review->getReviewText(),
            'links' => [
                'self' => $this->generateUrl('update_review', ['id' => $review->getId()]),
                'delete' => $this->generateUrl('delete_review', ['id' => $review->getId()])
            ]
        ]);
    }

    // Delete a review
    #[Route('/api/reviews/{id}', name: 'delete_review', methods: ['DELETE'])]
    public function deleteReviewApi(
        $id,
        ReviewRepository $reviewRepository,
        EntityManagerInterface $em
    ): JsonResponse
    {
        $review = $reviewRepository->find($id);
        if (!$review) {
            return $this->json(['message' => 'Review not found'], JsonResponse::HTTP_NOT_FOUND);
        }
        $em->remove($review);
        $em->flush();
        return $this->json(['message' => 'Review deleted'], JsonResponse::HTTP_OK);
    }
    #[Route('/api/reviews/{id}', name: 'get_single_review', methods: ['GET'])]
    public function getSingleReviewApi(Review $review): JsonResponse
    {
        return $this->json([
            'id' => $review->getId(),
            'rating' => $review->getRating(),
            'text' => $review->getReviewText(),
            'created_at' => $review->getCreatedAt()->format('Y-m-d H:i:s'),
            'user' => $review->getUser()?->getUserIdentifier(),
            'book' => $review->getBook()?->getTitle(),
        ]);
    }
    #[Route('/review/{reviewId}/comment', name: 'app_comment_create', methods: ['GET', 'POST'])]
    public function createComment(
        Request $request,
        EntityManagerInterface $entityManager,
        int $reviewId,
        BookRepository $bookRepository,
        ReviewRepository $reviewRepository
    ): Response
    {
        $review = $reviewRepository->find($reviewId);
        if (!$review) {
            throw $this->createNotFoundException('Review not found.');
        }
        $comment = new Comment();
        // Optional: get parent comment ID from query string for threading replies
        $parentId = $request->query->get('parent');
        if ($parentId) {
            $parent = $entityManager->getRepository(Comment::class)->find($parentId);
            if ($parent) {
                $comment->setParent($parent);
            }
        }
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setReview($review);
            $comment->setAuthor($this->getUser());
            $comment->setCreatedAt(new \DateTime());
            $entityManager->persist($comment);
            $entityManager->flush();
            return $this->redirectToRoute('app_review_show', ['id' => $reviewId]);
        }
        return $this->render('comment/create.html.twig', [
            'form' => $form->createView(),
            'review' => $review,
        ]);
    }
    #[Route('/review/{id}/upvote', name: 'review_upvote', methods: ['POST'])]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function upvote(Review $review, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        // Check if user already voted on this review
        $existingVote = $entityManager->getRepository(ReviewVote::class)
            ->findOneBy(['review' => $review, 'user' => $user]);

        if ($existingVote) {
            $this->addFlash('warning', 'You already upvoted this review.');
        } else {
            $vote = new ReviewVote();
            $vote->setReview($review);
            $vote->setUser($user);
            $entityManager->persist($vote);

            // Increment upvotes count on the review entity
            $review->incrementUpvotes();
            $entityManager->flush();

            $this->addFlash('success', 'Thanks for your upvote!');
        }

        return $this->redirectToRoute('book_show', ['id' => $review->getBook()->getId()]);
    }
}
