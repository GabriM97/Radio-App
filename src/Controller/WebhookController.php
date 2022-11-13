<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WebhookController
{
    #[Route('/webhook', 'webhook')]
    public function index(): Response
    {
        return new Response('Hello World');
    }
}