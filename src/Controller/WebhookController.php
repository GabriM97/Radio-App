<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

// Not required but should add the annotation: #[Route('/api', name: 'api_')]
class WebhookController extends AbstractController
{
    /**
     * Webhooks' requests entrypoint
     */
    #[Route('/webhook', name: 'webhook', methods: ['POST'], format: 'json')]
    public function index(Request $request): Response
    {
        $data = $this->getData($request);

        return $this->json($data);
    }

    /**
     * Returns the Request Data
     */
    private function getData(Request $request): array
    {
        $data = $request->request->all();
        if ($request->getContentType() === 'json') {
            $data = $request->toArray();
        }

        return $data;
    }
}