<?php

namespace App\Controller;

use App\Controller\AbstractController;
use App\Event\WebhookEvent;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
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
        $requestData = $this->getRequestData($request);

        $event = new WebhookEvent($requestData);
        $this->dispatcher->dispatch($event, $requestData['type']);

        return $this->json(['message' => Response::HTTP_OK]);
    }
}
