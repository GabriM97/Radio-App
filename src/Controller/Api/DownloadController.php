<?php

namespace App\Controller\Api;

use App\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api', name: 'api_')]
class DownloadController extends AbstractController
{
    #[Route('/download', name: 'download')]
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/DownloadController.php',
        ]);
    }
}
