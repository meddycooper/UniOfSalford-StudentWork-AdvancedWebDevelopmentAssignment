<?php

namespace App\Controller;
use App\Entity\Book;
use App\Entity\Review;
use App\Repository\ReviewRepository;
use App\Form\BookType;
use App\Form\BookSearchType;
use App\Model\BookSearchCriteria;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\String\Slugger\SluggerInterface;
use App\Entity\Comment;
use App\Form\CommentType;

class BookController extends AbstractController
{
    #[Route('/book', name: 'app_book')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        // Retrieve all books from the database using the Entity Manager
        //$books = $entityManager->getRepository(Book::class)->findAll();
        $books = $entityManager->getRepository(Book::class)->findBy([], ['title' => 'ASC']);
        // Render the 'book/list.html.twig.twig' template and pass the book's data
        return $this->render('book/list.html.twig', [
            'books' => $books
        ]);
    }
    #[Route('/book/new', name: 'book_new')]
    public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $book = new Book();
        $form = $this->createForm(BookType::class, $book);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $coverFile = $form->get('coverImageFile')->getData(); // ⬅️ match form field name

            if ($coverFile) {
                $originalFilename = pathinfo($coverFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $coverFile->guessExtension();

                try {
                    $coverFile->move(
                        $this->getParameter('cover_images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // Optional: log or display error
                }

                $book->setCoverImage($newFilename); // ⬅️ Save filename to DB
            }

            $entityManager->persist($book);
            $entityManager->flush();

            return $this->redirectToRoute('book_list');
        }

        return $this->render('book/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    #[Route('/books', name: 'book_list')] // New route to list books
    public function listBooks(EntityManagerInterface $entityManager): Response
    {
        // Retrieve all books from the database using the Entity Manager
        // $books = $entityManager->getRepository(Book::class)->findAll();
        $books = $entityManager->getRepository(Book::class)->findBy([], ['title' => 'ASC']);
        // Render the 'book/list.html.twig.twig' template and pass the book's data
        return $this->render('book/list.html.twig', [
            'books' => $books
        ]);
    }
    #[Route('/book/{id<\d+>}', name: 'book_show')]
    public function show(int $id, EntityManagerInterface $entityManager): Response
    {
        $book = $entityManager->getRepository(Book::class)->find($id);
        if (!$book) {
            throw $this->createNotFoundException('No book found for id ' . $id);
        }
        $user = $this->getUser();
        $isFavorite = false;
        if ($user && method_exists($user, 'getFavorites')) {
            foreach ($user->getFavorites() as $favorite) {
                if ($favorite->getBook() === $book) {
                    $isFavorite = true;
                    break;
                }
            }
        }
        // Get reviews for the book
        $reviews = $book->getReviews();
        $commentsByReview = [];
        foreach ($reviews as $review) {
            $comments = $entityManager->getRepository(Comment::class)->findBy(['review' => $review], ['createdAt' => 'ASC']);
            $commentsByReview[$review->getId()] = $comments;
        }
        return $this->render('book/show.html.twig', [
            'book' => $book,
            'reviews' => $reviews,
            'commentsByReview' => $commentsByReview,
            'isFavorite' => $isFavorite,
        ]);
    }
    #[Route('/book/search', name: 'book_search')]
    public function search(Request $request, BookRepository $bookRepository): Response
    {
        $searchCriteria = new BookSearchCriteria();
        $form = $this->createForm(BookSearchType::class, $searchCriteria);
        $form->handleRequest($request);

        $books = $bookRepository->findBySearchCriteria($searchCriteria);

        return $this->render('book/search.html.twig', [
            'form' => $form->createView(),
            'books' => $books,
        ]);
    }
    #[Route('/api/books/{bookId}/reviews', name: 'api_book_reviews', methods: ['GET'])]
    public function getReviewsByBook(int $bookId, ReviewRepository $reviewRepository): JsonResponse
    {
        $reviews = $reviewRepository->findBy(['book' => $bookId]);

        $data = array_map(function (Review $review) {
            return [
                'id' => $review->getId(),
                'rating' => $review->getRating(),
                'review_text' => $review->getReviewText(),
                'user' => $review->getUser()?->getUserIdentifier(),
                'created_at' => $review->getCreatedAt()->format('Y-m-d H:i:s'),
            ];
        }, $reviews);

        return $this->json($data);
    }
}
