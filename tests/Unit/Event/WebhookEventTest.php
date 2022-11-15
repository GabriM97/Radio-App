<?php

namespace App\Tests\Unit\Event;


use App\Event\WebhookEvent;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\Response;

class WebhookEventTest extends KernelTestCase
{
    protected WebhookEvent $event;

    public function setUp(): void
    {
        parent::setUp();
        
        $this->event = new WebhookEvent(['data' => []]);
    }

    /**
     * Test getRequestData() returns an array
     */
    public function testGetRequestDataReturnsArray(): void
    {
        $requestData = $this->event->getRequestData();
        $this->assertIsArray($requestData);
    }

    /**
     * Test getData() returns an array
     */
    public function testGetDataReturnsArray(): void
    {
        $data = $this->event->getData();
        $this->assertIsArray($data);
    }

    /**
     * Test getData() returns an empty array when data is not defined
     */
    public function testGetDataReturnsEmptyArrayWhenDataIsNotDefined(): void
    {
        $data = (new WebhookEvent([]))->getData();
        $this->assertIsArray($data);
        $this->assertEmpty($data);
    }

    /**
     * Test getResponse() returns a Response instance
     */
    public function testGetResponseReturnsAResponseInstance(): void
    {
        $response = $this->event->getResponse();
        $this->assertInstanceOf(Response::class, $response);
    }

}