<?php

namespace App\Controller;

use App\Controller\AbstractController;
use App\Event\WebhookEvent;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WebhookController extends AbstractController
{
    /**
     * Webhooks' requests entrypoint
     */
    #[Route('/webhook', name: 'webhook', methods: ['POST'], format: 'json')]
    public function index(Request $request): Response
    {
        $requestData = $this->getRequestData($request);

        $event = new WebhookEvent($requestData);
        $this->dispatcher->dispatch($event, $requestData['type']);

        return $event->getResponse();
    }
}
