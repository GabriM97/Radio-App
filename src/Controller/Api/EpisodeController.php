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

    #[Route('/episode/create', name: 'episode_create', methods: ['POST'])]
    public function store(
        Request $request, 
        EpisodeRepository $episodeRepository
    ): JsonResponse {

        $data = $this->getRequestData($request);
        
        $episode = new Episode();
        $episode->setTitle($data['title']);
        $episode->setTopic($data['topic']);

        $episodeRepository->save($episode, true);

        return $this->json(['id' => $episode->getId()]);
    }

    #[Route('/episode/{id}/stats', name: 'episode_stats', methods: ['GET', 'HEAD'])]
    public function getStatsDaily(
        int $id,
        Request $request,
        DownloadRepository $donwloadRepository,
    ): JsonResponse {

        $lastDays = $request->query->get('last_days', 7);
        $fromDate = $request->query->get('from_date', null);
        $toDate = $request->query->get('to_date', null);

        list($fromDate, $toDate) = $this->getDatesRange($lastDays, $fromDate, $toDate);

        $downloads = $donwloadRepository->getDownloadsBetweenDatesByEpisode($id, $fromDate, $toDate);

        $dailyStats = $this->groupDataByDay(array_map(
            fn(Download $download) => [
                'id' => $download->getId(),
                'datetime' => $download->getDatetime(),
            ],
            $downloads
        ));

        return $this->json(array_map(
            fn(array $dailyDownloads) => count($dailyDownloads),
            $dailyStats
        ));
    }
}