<?php

namespace App\Controller;

use App\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

// Not required but should add the annotation: #[Route('/api', name: 'api_')]
class WebhookController extends AbstractController
{
    /**
     * Webhooks' requests entrypoint
     */
    #[Route('/webhook', name: 'webhook', methods: ['POST'], format: 'json')]
    public function index(Request $request): JsonResponse
    {
        $data = $this->getRequestData($request);

        return $this->json($data);
    }
}