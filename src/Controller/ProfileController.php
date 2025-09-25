<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/profile')]
#[IsGranted('ROLE_USER')]
class ProfileController extends AbstractController
{
    #[Route('', name: 'app_profile_show')]
    public function show(): Response
    {
        // Portfolio-safe: mock user data
        $user = [
            'username' => 'demo_user',
            'email' => 'demo@example.com',
            'profilePicture' => '/images/demo-profile.png'
        ];

        return $this->render('profile/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/edit', name: 'app_profile_edit')]
    public function edit(): Response
    {
        // Portfolio-safe: simulate form submission and profile picture upload
        $this->addFlash('success', 'Profile updated successfully (mock).');

        // Redirect to mock profile page
        return $this->redirectToRoute('app_profile_show');
    }
}
