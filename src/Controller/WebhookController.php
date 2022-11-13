<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;

class WebhookController
{
    public function index(): Response
    {
        return new Response('Hello World');
    }
}