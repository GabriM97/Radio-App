<?php

namespace App\Controller\Api;

use App\Controller\AbstractController;
use App\Entity\Download;
use App\Entity\Episode;
use App\Entity\User;
use App\Repository\DownloadRepository;
use App\Repository\EpisodeRepository;
use App\Repository\PodcastRepository;
use App\Repository\UserRepository;
use App\Trait\DateTrait;
use DateTimeImmutable;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api', name: 'api_')]
class EpisodeController extends AbstractController
{
    use DateTrait;

    /**
     * Creates and stores a new Episode.
     *
     * @param Request $request
     * @param EpisodeRepository $episodeRepository
     * 
     * @return JsonResponse
     */
    #[Route('/episode/create', name: 'episode_create', methods: ['POST'])]    
    public function store(
        Request $request, 
        EpisodeRepository $episodeRepository, 
        PodcastRepository $podcastRepository,
        UserRepository $userRepository
    ): JsonResponse {
        $data = $this->getRequestData($request);
        
        // create the episode
        $episode = new Episode();
        $episode->setTitle($data['title']);
        $episode->setTopic($data['topic']);
        $episode->setPodcast($podcastRepository->find($data['podcast_id']));
        foreach($data['hosts'] as $host) {
            $episode->addHost($userRepository->find($host['id']));
        }

        // store the episode
        $episodeRepository->save($episode, true);

        return $this->json(['id' => $episode->getId()]);
    }

    #[Route('/episode/{id}', name: 'episode', methods: ['GET', 'HEAD'])]
    public function show(int $id, EpisodeRepository $episodeRepository): JsonResponse
    {
        $episode = $episodeRepository->find($id);

        return $this->json([
            'id' => $episode->getId(),
            'title' => $episode->getTitle(),
            'topic' => $episode->getTopic(),
            'podcast_id' => $episode->getPodcast()->getId(),
            'hosts' => array_map(
                fn(User $host) => ['id' => $host->getId(), 'email' => $host->getEmail()], 
                $episode->getHosts()->toArray()
            ),
            'downloads' => $episode->getDownloads()->count(),
        ]);
    }

    #[Route('/episode/{id}/download', name: 'episode_download', methods: ['GET', 'HEAD'])]
    public function download(int $id, EpisodeRepository $episodeRepository, DownloadRepository $downloadRepository): JsonResponse
    {        
        $episode = $episodeRepository->find($id);

        $download = new Download();
        $download->setEpisode($episode);
        $download->setDatetime(new DateTimeImmutable());

        $downloadRepository->save($download, true);

        return $this->json(['id' => $download->getId()]);
    }

    #[Route('/episode/{id}/stats', name: 'episode_stats', methods: ['GET', 'HEAD'])]
    public function getStats(
        int $id,
        Request $request,
        DownloadRepository $donwloadRepository,
    ): JsonResponse {

        // $episode = $episodeRepository->find($id);

        $lastDays = $request->query->get('last_days', 7);
        $fromDate = $request->query->get('from_date', null);
        $toDate = $request->query->get('to_date', null);

        list($fromDate, $toDate) = $this->getDatesRange($lastDays, $fromDate, $toDate);

        $downloads = $donwloadRepository->getDownloadsBetweenDatesByEpisode($id, $fromDate, $toDate);

        return $this->json(array_map(
            fn(Download $download) => [
                'id' => $download->getId(),
                // 'episode_id' => $download->getEpisode()->getId(),
                'datetime' => $download->getDatetime()->format('Y-m-d H:i:s'),
            ],
            $downloads
        ));
    }

    /**
     * Retrieves the episode stats between a defined range of dates,
     * groups them by day returning the amount of downloads per day.
     * 
     * @param int $id
     * @param Request $request
     * @param DownloadRepository $donwloadRepository
     *
     * @return JsonResponse
     */
    #[Route('/episode/{id}/stats/daily', name: 'episode_stats_daily', methods: ['GET', 'HEAD'])]
    public function getStatsDaily(
        int $id,
        Request $request,
        DownloadRepository $donwloadRepository,
    ): JsonResponse {

        // $episode = $episodeRepository->find($id);

        // get query parameters
        $lastDays = $request->query->get('last_days', 7);
        $fromDate = $request->query->get('from_date', null);
        $toDate = $request->query->get('to_date', null);

        // resolve what's the date range we will use
        list($fromDate, $toDate) = $this->getDatesRange($lastDays, $fromDate, $toDate);

        // retrieve all the downloads between the defined dates
        $downloads = $donwloadRepository->getDownloadsBetweenDatesByEpisode($id, $fromDate, $toDate);

        // group stats by day
        $dailyStats = $this->groupDataByDay(array_map(
            fn(Download $download) => [
                'id' => $download->getId(),
                'datetime' => $download->getDatetime(),
            ],
            $downloads
        ));

        // only set the amount of downloads per day in the response
        return $this->json(array_map(
            fn(array $dailyDownloads) => count($dailyDownloads),
            $dailyStats
        ));
    }
}