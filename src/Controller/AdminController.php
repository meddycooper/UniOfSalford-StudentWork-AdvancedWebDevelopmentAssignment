<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\User;
use App\Entity\Book;
use App\Entity\Review; // Add this at the top
use App\Form\BookType;
use App\Form\UserRoleType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\JsonResponse;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin')]
    #[IsGranted('ROLE_ADMIN')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        // Most reviewed books: order by number of reviews descending, limit 5
        $mostReviewedBooks = $entityManager->createQuery(
            'SELECT b, COUNT(r.id) AS reviewsCount
         FROM App\Entity\Book b
         LEFT JOIN b.reviews r
         GROUP BY b
         ORDER BY reviewsCount DESC'
        )
            ->setMaxResults(5)
            ->getResult();

        // Top-rated books: order by average rating descending, limit 5
        $topRatedBooks = $entityManager->createQuery(
            'SELECT b, AVG(r.rating) AS avgRating
         FROM App\Entity\Book b
         LEFT JOIN b.reviews r
         GROUP BY b
         ORDER BY avgRating DESC'
        )
            ->setMaxResults(5)
            ->getResult();

        // Most active users: order by number of reviews written descending, limit 5
        $mostActiveUsers = $entityManager->createQuery(
            'SELECT u, COUNT(r.id) AS reviewsCount
         FROM App\Entity\User u
         LEFT JOIN u.reviews r
         GROUP BY u
         ORDER BY reviewsCount DESC'
        )
            ->setMaxResults(5)
            ->getResult();

        return $this->render('admin/index.html.twig', [
            'mostReviewedBooks' => $mostReviewedBooks,
            'topRatedBooks' => $topRatedBooks,
            'mostActiveUsers' => $mostActiveUsers,
        ]);
    }
    #[Route('/admin/users', name: 'admin_users')]
    #[IsGranted('ROLE_ADMIN')]
    public function manageUsers(Request $request, EntityManagerInterface $entityManager): Response
    {
        $users = $entityManager->getRepository(User::class)->findAll();

        return $this->render('admin/manage_users.html.twig', [
            'users' => $users,
        ]);
    }
    #[Route('/admin/user/{id}/edit', name: 'admin_edit_user')]
    #[IsGranted('ROLE_ADMIN')]
    public function editUserRole(User $user, Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UserRoleType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('admin_users');
        }

        return $this->render('admin/edit.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }
    #[Route('/admin/books', name: 'admin_books')]
    #[IsGranted('ROLE_ADMIN')]
    public function manageBooks(EntityManagerInterface $entityManager): Response
    {
        //$books = $entityManager->getRepository(Book::class)->findAll();
        $books = $entityManager->getRepository(Book::class)->findBy([], ['title' => 'ASC']);
        return $this->render('admin/manage_books.html.twig', [
            'books' => $books,
        ]);
    }

    #[Route('/admin/book/{id}/edit', name: 'admin_edit_book')]
    #[IsGranted('ROLE_ADMIN')]
    public function editBook(int $id, Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $book = $entityManager->getRepository(Book::class)->find($id);
        if (!$book) {
            throw $this->createNotFoundException('Book not found');
        }

        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $coverFile = $form->get('coverImageFile')->getData();
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
                    // handle exception
                }

                $book->setCoverImage($newFilename);
            }

            $entityManager->flush();

            return $this->redirectToRoute('admin_books');
        }

        return $this->render('admin/edit_book.html.twig', [
            'form' => $form->createView(),
            'book' => $book,
        ]);
    }

    #[Route('/admin/reviews', name: 'admin_reviews')]
    #[IsGranted('ROLE_ADMIN')]
    public function manageReviews(EntityManagerInterface $entityManager): Response
    {
        $reviews = $entityManager->getRepository(Review::class)->findBy([], ['created_at' => 'DESC']);
        return $this->render('admin/manage_reviews.html.twig', [
            'reviews' => $reviews,
        ]);
    }

    #[Route('/admin/review/{id}/delete', name: 'admin_delete_review', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function deleteReview(Review $review, EntityManagerInterface $entityManager, Request $request): RedirectResponse
    {
        // Optionally check CSRF token here for safety
        if ($this->isCsrfTokenValid('delete-review' . $review->getId(), $request->request->get('_token'))) {
            $entityManager->remove($review);
            $entityManager->flush();
            $this->addFlash('success', 'Review deleted successfully.');
        } else {
            $this->addFlash('error', 'Invalid CSRF token.');
        }

        return $this->redirectToRoute('admin_reviews');
    }

    #[Route('/admin/review/{id}/toggle-flag', name: 'admin_toggle_flag_review', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function toggleFlagReview(Review $review, EntityManagerInterface $entityManager, Request $request): RedirectResponse
    {
        if ($this->isCsrfTokenValid('flag-review' . $review->getId(), $request->request->get('_token'))) {
            $review->setFlagged(!$review->getFlagged());
            $entityManager->flush();

            $this->addFlash('success', $review->getFlagged() ? 'Review flagged.' : 'Review unflagged.');
        } else {
            $this->addFlash('error', 'Invalid CSRF token.');
        }

        return $this->redirectToRoute('admin_reviews');
    }
}
