<?php

namespace App\Event;

use Symfony\Contracts\EventDispatcher\Event;

class WebhookEvent extends Event
{
    public function __construct(protected array $requestData)
    {
    }

    public function getRequestData(): array
    {
        return $this->requestData;
    }

    public function getData(): array
    {
        return $this->requestData['data'] ?? [];
    }
}
