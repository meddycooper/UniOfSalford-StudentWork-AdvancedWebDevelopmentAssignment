<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(Request $request): Response
    {
        // Portfolio-safe: simulate registration form submission
        if ($request->isMethod('POST')) {
            // Mock successful registration
            $this->addFlash('success', 'Registration successful (mock).');

            // Redirect to books page (mock)
            return $this->redirectToRoute('book_list');
        }

        // Render mock registration form
        return $this->render('registration/register.html.twig', [
            'registrationForm' => null, // just a placeholder
        ]);
    }

    #[Route('/verify/email', name: 'app_verify_email')]
    public function verifyUserEmail(Request $request): Response
    {
        // Portfolio-safe: simulate email verification
        $this->addFlash('success', 'Email verified (mock).');

        // Redirect to registration page
        return $this->redirectToRoute('app_register');
    }
}
