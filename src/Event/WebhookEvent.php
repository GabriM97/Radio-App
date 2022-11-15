<?php

namespace App\Event;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\EventDispatcher\Event;

class WebhookEvent extends Event
{
    /**
     * @var Response $response
     */
    protected Response $response;

    public function __construct(protected array $requestData)
    {
        // init an empty Response object that could be used by the Listeners
        $this->response = new Response();
    }

    /**
     * Returns all the Request data. Might cointain Event information.
     * 
     * @return array 
     */
    public function getRequestData(): array
    {
        return $this->requestData;
    }

    /**
     * Returns the actual 'data' field sent by the Request.
     * 
     * @return array 
     */
    public function getData(): array
    {
        return $this->requestData['data'] ?? [];
    }

    /**
     * Returns the Response object. Used by Listeners to return a custom response.
     * 
     * @return Response 
     */
    public function getResponse(): Response
    {
        return $this->response;
    }
}
