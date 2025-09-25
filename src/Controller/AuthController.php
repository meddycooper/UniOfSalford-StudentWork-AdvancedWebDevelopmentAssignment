<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Security;

class AuthController extends AbstractController
{
    // Portfolio demonstration of login endpoint
    // Note: JWT authentication logic removed for safety
    public function login(Request $request, Security $security): JsonResponse
    {
        $user = $security->getUser();

        // Normally, a JWT token would be generated here using LexikJWTAuthenticationBundle
        // For portfolio purposes, we just return a sample JSON response
        return $this->json([
            'status' => 'success',
            'message' => $user ? 'User is authenticated' : 'No user logged in',
            'user' => $user ? $user->getUsername() : null,
        ]);
    }
}
