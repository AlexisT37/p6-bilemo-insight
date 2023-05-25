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
    #[Route('/phones/test', name: 'app_test', methods: ['GET'])]
    public function index(): JsonResponse
    {
        return new JsonResponse([
            'message' => 'Welcome to your new phone controller, this is a test!',
            'path' => 'src/Controller/PhoneController.php',
        ]);
    }

    // Function to return all phones in the database with a JSON response
    #[Route('/api/phones', name: 'app_phones', methods: ['GET'])]
    public function getPhones(Request $request, PhoneRepository $phoneRepository, SerializerInterface $serializer): JsonResponse
    {
        $phones = $phoneRepository->findAllWithPagination(1, 5);

        $jsonPhones = $serializer->serialize($phones, 'json');

        return new JsonResponse($jsonPhones, Response::HTTP_OK, [], true);
    }
}
