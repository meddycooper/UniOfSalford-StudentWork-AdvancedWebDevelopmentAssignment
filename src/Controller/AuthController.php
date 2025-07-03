<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Security;

class AuthController extends AbstractController
{
    public function login(Request $request, Security $security): JsonResponse
    {
        $user = $security->getUser();

        return $this->json([
            'token' => $this->get('lexik_jwt_authentication.yaml.jwt_manager')->create($user),
        ]);
    }
}