<?php

namespace App\Tests\Unit\EventListener;

use App\Entity\Download;
use App\Entity\Episode;
use App\Event\WebhookEvent;
use App\EventListener\EpisodeDownloadedListener;
use App\Repository\DownloadRepository;
use App\Repository\EpisodeRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Response;

class EpisodeDownloadedListenerTest extends KernelTestCase
{
    protected EpisodeRepository $episodeRepository;
    protected DownloadRepository $downloadRepository;
    protected EpisodeDownloadedListener $eventListener;
    protected WebhookEvent $event;

    public function setUp(): void
    {
        parent::setUp();

        $this->episodeRepository = $this->createMock(EpisodeRepository::class);
        $this->downloadRepository = $this->createMock(DownloadRepository::class);
        $this->event = $this->createMock(WebhookEvent::class);
    }

    /**
     * Test onEpisodeDownloaded() stores a new Download
     */
    public function testOnEpisodeDownloadedStoresNewDownload(): void
    {
        // mock getData() method
        $this->event
            ->expects($this->once())
            ->method('getData')
            ->will($this->returnValue(['episode_id' => 10]));

        // mock find() method
        $this->episodeRepository
            ->expects($this->once())
            ->method('find')
            ->with($this->greaterThan(0))
            ->will($this->returnValue(new Episode()));

        // mock save() method
        $this->downloadRepository
            ->expects($this->once())
            ->method('save')
            ->with($this->isInstanceOf(Download::class), $this->isTrue());

        // mock getResponse() method
        $this->event
            ->expects($this->once())
            ->method('getResponse')
            ->will($this->returnValue(new Response()));

        $eventListener = new EpisodeDownloadedListener($this->episodeRepository, $this->downloadRepository); 
        $eventListener->onEpisodeDownloaded($this->event);
    }
}