<?php

namespace App\Event;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\EventDispatcher\Event;

class WebhookEvent extends Event
{
    protected Response $response;

    public function __construct(protected array $requestData)
    {
        $this->response = new Response();
    }

    public function getRequestData(): array
    {
        return $this->requestData;
    }

    public function getData(): array
    {
        return $this->requestData['data'] ?? [];
    }

    public function getResponse(): Response
    {
        return $this->response;
    }
}
