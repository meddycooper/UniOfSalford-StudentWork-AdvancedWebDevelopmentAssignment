<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
// Use Entities
use App\Entity\User;
use App\Entity\Book;
use App\Entity\Review;
// Use Forms
use App\Form\BookType;
use App\Form\UserRoleType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\String\Slugger\SluggerInterface;

class AdminController extends AbstractController
{
    // Admin dashboard
    #[Route('/admin', name: 'app_admin')]
    #[IsGranted('ROLE_ADMIN')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        // Example queries to demonstrate dashboard features
        $mostReviewedBooks = $entityManager->createQuery(
            'SELECT b, COUNT(r.id) AS reviewsCount
             FROM App\Entity\Book b
             LEFT JOIN b.reviews r
             GROUP BY b
             ORDER BY reviewsCount DESC'
        )->setMaxResults(5)->getResult();

        $topRatedBooks = $entityManager->createQuery(
            'SELECT b, AVG(r.rating) AS avgRating
             FROM App\Entity\Book b
             LEFT JOIN b.reviews r
             GROUP BY b
             ORDER BY avgRating DESC'
        )->setMaxResults(5)->getResult();

        $mostActiveUsers = $entityManager->createQuery(
            'SELECT u, COUNT(r.id) AS reviewsCount
             FROM App\Entity\User u
             LEFT JOIN u.reviews r
             GROUP BY u
             ORDER BY reviewsCount DESC'
        )->setMaxResults(5)->getResult();

        return $this->render('admin/index.html.twig', [
            'mostReviewedBooks' => $mostReviewedBooks,
            'topRatedBooks' => $topRatedBooks,
            'mostActiveUsers' => $mostActiveUsers,
        ]);
    }

    // Manage users
    #[Route('/admin/users', name: 'admin_users')]
    #[IsGranted('ROLE_ADMIN')]
    public function manageUsers(EntityManagerInterface $entityManager): Response
    {
        $users = $entityManager->getRepository(User::class)->findAll();

        return $this->render('admin/manage_users.html.twig', [
            'users' => $users,
        ]);
    }

    // Edit user role (safe example)
    #[Route('/admin/user/{id}/edit', name: 'admin_edit_user')]
    #[IsGranted('ROLE_ADMIN')]
    public function editUserRole(User $user, Request $request, EntityManagerInterface $entityManager): Response
    {
        // This demonstrates form handling in Symfony
        $form = $this->createForm(UserRoleType::class, $user);
        $form->handleRequest($request);

        // Save changes if submitted (safe demonstration)
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            // Redirect after edit
            return $this->redirectToRoute('admin_users');
        }

        return $this->render('admin/edit.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }

    // Manage books
    #[Route('/admin/books', name: 'admin_books')]
    #[IsGranted('ROLE_ADMIN')]
    public function manageBooks(EntityManagerInterface $entityManager): Response
    {
        // Example: Fetch books for admin view
        $books = $entityManager->getRepository(Book::class)->findBy([], ['title' => 'ASC']);
        return $this->render('admin/manage_books.html.twig', [
            'books' => $books,
        ]);
    }

    // Edit book (example of file upload handling removed for safety)
    #[Route('/admin/book/{id}/edit', name: 'admin_edit_book')]
    #[IsGranted('ROLE_ADMIN')]
    public function editBook(Book $book, Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush(); // Save edits
            return $this->redirectToRoute('admin_books');
        }

        return $this->render('admin/edit_book.html.twig', [
            'form' => $form->createView(),
            'book' => $book,
        ]);
    }

    // Manage reviews (display only)
    #[Route('/admin/reviews', name: 'admin_reviews')]
    #[IsGranted('ROLE_ADMIN')]
    public function manageReviews(EntityManagerInterface $entityManager): Response
    {
        $reviews = $entityManager->getRepository(Review::class)->findBy([], ['created_at' => 'DESC']);
        return $this->render('admin/manage_reviews.html.twig', [
            'reviews' => $reviews,
        ]);
    }

    /*
    The following actions for deleting or flagging reviews are removed/commented out
    for portfolio safety. They modify the database directly and could expose sensitive logic.

    #[Route('/admin/review/{id}/delete', ...)]
    #[Route('/admin/review/{id}/toggle-flag', ...)]
    */
}
