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

    public function onEpisodeDownloaded(WebhookEvent $event)
    {
        $data = $event->getData();

        $episode = $this->episodeRepository->find($data['episode_id']);

        $download = new Download();
        $download->setEpisode($episode);
        $download->setDatetime(new DateTimeImmutable());    // or $event->getRequestData()['occurred_at']

        $this->downloadRepository->save($download, true);

        $response = $event->getResponse();
        $response->setContent(json_encode(['id' => $download->getId()]));
        // $response->headers->set('Content-Type', 'application/json');
    }
}
