<?php

namespace App\Controller;

use Symfony\Component\Serializer\SerializerInterface;
use App\Repository\PhoneRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PhoneController extends AbstractController
{
    #[Route('/phone/test', name: 'app_test', methods: ['GET'])]
    public function index(): JsonResponse
    {
        return new JsonResponse([
            'message' => 'Welcome to your new phone controller, this is a test!',
            'path' => 'src/Controller/BookController.php',
        ]);
    }

    // Function to return all phones in the database with a JSON response
    #[Route('/phone', name: 'app_phone', methods: ['GET'])]
    public function getPhones(Request $request, PhoneRepository $phoneRepository, SerializerInterface $serializer): JsonResponse
    {
        $phones = $phoneRepository->findAllWithPagination(2, 5);
        // $phones = $phoneRepository->findAll();

        $jsonPhones = $serializer->serialize($phones, 'json');

        return new JsonResponse($jsonPhones, Response::HTTP_OK, [], true);
    }
}
