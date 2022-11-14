<?php

namespace App\Controller\Api;

use App\Controller\AbstractController;
use App\Entity\Episode;
use App\Entity\Podcast;
use App\Repository\PodcastRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api', name: 'api_')]
class PodcastController extends AbstractController
{
    #[Route('/podcast/create', name: 'podcast_create', methods: ['POST'])]
    public function store(
        Request $request, 
        PodcastRepository $podcastRepository, 
        UserRepository $userRepository
    ): JsonResponse {
        $data = $this->getRequestData($request);
        
        $podcast = new Podcast();
        $podcast->setName($data['name']);
        $podcast->setType($data['type']);
        $podcast->setCreator($userRepository->find($data['creator_id']));

        $podcastRepository->save($podcast, true);

        return $this->json(['id' => $podcast->getId()]);
    }

    #[Route('/podcast/{id}', name: 'podcast', methods: ['GET', 'HEAD'])]
    public function show(int $id, PodcastRepository $podcastRepository): JsonResponse
    {
        $podcast = $podcastRepository->find($id);

        return $this->json([
            'id' => $podcast->getId(),
            'name' => $podcast->getName(),
            'type' => $podcast->getType(),
            'creator_id' => $podcast->getCreator()->getId(),
            'episodes' => array_map(
                fn(Episode $episode) => ['id' => $episode->getId()], 
                $podcast->getEpisodes()->toArray()
            ),
        ]);
    }
}
