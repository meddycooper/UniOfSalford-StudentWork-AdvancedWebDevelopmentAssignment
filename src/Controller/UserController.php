<?php
namespace App\Controller;

use App\Repository\ReviewRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Route('/my-reviews', name: 'user_reviews')]
    public function myReviews(ReviewRepository $reviewRepository): Response
    {
        $user = $this->getUser();
        if (!$user) {
            throw $this->createAccessDeniedException('You must be logged in to view your reviews.');
        }

        $reviews = $reviewRepository->findBy(['user' => $user]);

        return $this->render('user/my_reviews.html.twig', [
            'reviews' => $reviews,
        ]);
    }
}
