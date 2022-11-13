<?php

namespace App\Controller\Api;

use App\Controller\AbstractController;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\UserRepository;

#[Route('/api', name: 'api_')]
class UserController extends AbstractController
{
    #[Route('/user/create', name: 'user_create', methods: ['POST'])]
    public function store(Request $request, UserRepository $repository): Response
    {
        $data = $this->getRequestData($request);
        
        $user = new User();
        $user->setEmail($data['email']);
        $user->setName($data['name']);

        $repository->save($user, true);

        return $this->redirectToRoute('api_user', ['id' => $user->getId()]);
    }

    #[Route('/user/{id}', name: 'user', methods: ['GET', 'HEAD'])]
    public function show(int $id, UserRepository $repository): JsonResponse
    {
        $user = $repository->find($id);

        return $this->json([
            'id' => $user->getId(),
            'name' => $user->getName(),
            'email' => $user->getEmail(),
        ]);
    }
}
