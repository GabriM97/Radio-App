<?php

namespace App\Controller\Api;

use App\Controller\AbstractController;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\UserRepository;

#[Route('/api', name: 'api_')]
class UserController extends AbstractController
{
    #[Route('/user/create', name: 'user_create', methods: ['POST'])]
    public function store(Request $request, EntityManagerInterface $manager): Response
    {
        $data = $this->getRequestData($request);
        
        $user = new User();
        $user->setEmail($data['email']);
        $user->setName($data['name']);

        /** @var UserRepository repository */
        $repository = $manager->getRepository(User::class);
        $repository->save($user, true);

        return $this->redirectToRoute('api_user', ['user' => $user]);
    }

    #[Route('/user/{user}', name: 'user', methods: ['GET', 'HEAD'])]
    public function show(User $user): JsonResponse
    {
        return $this->json($user);
    }
}
