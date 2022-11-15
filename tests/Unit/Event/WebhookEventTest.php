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
        $this->event = new WebhookEvent(['data' => []]);
    }

    public function testGetRequestDataReturnsArray(): void
    {
        $requestData = $this->event->getRequestData();
        $this->assertIsArray($requestData);
    }

    public function testGetDataReturnsArray(): void
    {
        $data = $this->event->getData();
        $this->assertIsArray($data);
    }

    public function testGetDataReturnsEmptyArrayWhenDataIsNotDefined(): void
    {
        $data = (new WebhookEvent([]))->getData();
        $this->assertIsArray($data);
        $this->assertEmpty($data);
    }

    public function testGetResponseReturnsAResponseInstance(): void
    {
        $response = $this->event->getResponse();
        $this->assertInstanceOf(Response::class, $response);
    }

}