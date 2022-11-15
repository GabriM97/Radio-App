<?php

namespace App\EventListener;

use App\Entity\Download;
use App\Event\WebhookEvent;
use App\Repository\DownloadRepository;
use App\Repository\EpisodeRepository;
use DateTimeImmutable;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\Response;

#[AsEventListener(event: 'episode.downloaded')]
class EpisodeDownloadedListener
{
    public function __construct(
        protected EpisodeRepository $episodeRepository,
        protected DownloadRepository $downloadRepository,
    ) {
    }

    /**
     * Handles the 'episode.downloaded' event type.
     * 
     * @param WebhookEvent $event
     * 
     * @return void
     */
    public function onEpisodeDownloaded(WebhookEvent $event): void
    {
        // get the download data from the event
        $data = $event->getData();

        // search for the episode by id
        $episode = $this->episodeRepository->find($data['episode_id']);

        // initialise the new download instance
        $download = new Download();
        $download->setEpisode($episode);
        $download->setDatetime(new DateTimeImmutable());    // or $event->getRequestData()['occurred_at']

        // store the download
        $this->downloadRepository->save($download, true);

        // set the event response
        $response = $event->getResponse();
        $response->setContent(json_encode(['id' => $download->getId()]));
        // $response->headers->set('Content-Type', 'application/json');
    }
}
