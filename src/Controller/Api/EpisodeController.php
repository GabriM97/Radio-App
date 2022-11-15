<?php

namespace App\Controller\Api;

use App\Controller\AbstractController;
use App\Entity\Download;
use App\Entity\Episode;
use App\Repository\DownloadRepository;
use App\Repository\EpisodeRepository;
use App\Trait\DateTrait;
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
        EpisodeRepository $episodeRepository
    ): JsonResponse {

        $data = $this->getRequestData($request);
        
        // create the episode
        $episode = new Episode();
        $episode->setTitle($data['title']);
        $episode->setTopic($data['topic']);

        // store the episode
        $episodeRepository->save($episode, true);

        return $this->json(['id' => $episode->getId()]);
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
    #[Route('/episode/{id}/stats', name: 'episode_stats', methods: ['GET', 'HEAD'])]
    public function getStatsDaily(
        int $id,
        Request $request,
        DownloadRepository $donwloadRepository,
    ): JsonResponse {

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