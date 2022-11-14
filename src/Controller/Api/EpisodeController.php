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
use DateTimeImmutable;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api', name: 'api_')]
class EpisodeController extends AbstractController
{
    #[Route('/episode/create', name: 'episode_create', methods: ['POST'])]
    public function store(
        Request $request, 
        EpisodeRepository $episodeRepository, 
        PodcastRepository $podcastRepository,
        UserRepository $userRepository
    ): JsonResponse {
        $data = $this->getRequestData($request);
        
        $episode = new Episode();
        $episode->setTitle($data['title']);
        $episode->setTopic($data['topic']);
        $episode->setPodcast($podcastRepository->find($data['podcast_id']));
        foreach($data['hosts'] as $host) {
            $episode->addHost($userRepository->find($host['id']));
        }

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

    // #[Route('/episode/{id}/stats', name: 'episode_stats', methods: ['GET', 'HEAD'], requirements: ['page' => '\d+'])]
    // public function getStats(int $id): JsonResponse
    // {
    //     return $this->json([
    //         'stats' => [
    //             'id' => $id,
    //             'total_views' => 1000,
    //             'total_downloads' => 200,
    //             '01/01/2022' => [
    //                 'views' => 100,
    //                 'downloads' => 25,
    //             ],
    //         ]
    //     ]);
    // }
}