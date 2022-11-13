<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api', name: 'api_')]
class EpisodeController extends AbstractController
{
    #[Route('/episode/{id}/stats', name: 'episode_stats', methods: ['GET', 'HEAD'], requirements: ['page' => '\d+'])]
    public function getStats(int $id): Response
    {
        return $this->json([
            'stats' => [
                'id' => $id,
                'total_views' => 1000,
                'total_downloads' => 200,
                '01/01/2022' => [
                    'views' => 100,
                    'downloads' => 25,
                ],
            ]
        ]);
    }
}