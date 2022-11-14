<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController as SymfonyController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;

abstract class AbstractController extends SymfonyController
{
    public function __construct(protected EventDispatcherInterface $dispatcher)
    {
    }

    /**
     * Returns the Request Data
     */
    public static function getRequestData(Request $request): array
    {
        $data = $request->request->all();
        if ($request->getContentType() === 'json') {
            $data = $request->toArray();
        }

        return $data;
    }
}
