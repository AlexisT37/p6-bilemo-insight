<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class PhoneController extends AbstractController
{
    #[Route('/phone', name: 'app_phones', methods: ['GET'])]
    public function index(): JsonResponse
    {
        return new JsonResponse([
            'message' => 'Welcome to your new phone controller!',
            'path' => 'src/Controller/BookController.php',
        ]);
    }
}
