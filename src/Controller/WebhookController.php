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
     * Webhook requests entrypoint. A WebhookEvent is dispatched at every rerquest. 
     * The event fired depends on the passed 'type' parameter.
     * 
     * @param Request $request
     *
     * @return Response
     */
    #[Route('/webhook', name: 'webhook', methods: ['POST'], format: 'json')]
    public function index(Request $request): Response
    {
        $requestData = $this->getRequestData($request);

        // create the generic Webhook event containing the request data and fire it
        $event = new WebhookEvent($requestData);
        $this->dispatcher->dispatch($event, $requestData['type']);

        return $event->getResponse();
    }
}
