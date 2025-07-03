<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api', name: 'api_')]
class DataController extends AbstractController
{
    #[Route('/data', name: 'get_data', methods: ['GET'])]
    public function getData(): JsonResponse
    {
        return $this->json([
            'status' => 'success',
            'data' => ['sample' => 'api_response']
        ]);
    }
}
