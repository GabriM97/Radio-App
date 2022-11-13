<?php

namespace App\Controller\Api;

use App\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api', name: 'api_')]
class PodcastController extends AbstractController
{
    #[Route('/podcast', name: 'podcast')]
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/PodcastController.php',
        ]);
    }
}
