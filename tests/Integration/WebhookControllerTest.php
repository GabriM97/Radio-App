<?php

namespace App\Tests\Integration\Controller;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class WebhookControllerTest extends WebTestCase
{
    protected KernelBrowser $client;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = static::createClient();
    }

    /**
     * Test /webhook endpoint
     */
    public function testWebhookEndpoint()
    {
        $this->client->request(
            'POST', 
            '/webhook', 
            [   
                'type' => 'event.name',
                'data' => ['event data']
            ]
        );

        $this->assertResponseIsSuccessful();
    }
    
    
}