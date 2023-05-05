<?php

namespace App\Controller;

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
    public function getPhones(Request $request, PhoneRepository $phoneRepository): JsonResponse
    {
        $phones = $phoneRepository->findAllWithPagination(3, 5);
        $data = [];
        foreach ($phones as $phone) {
            $data[] = [
                'id' => $phone->getId(),
                'name' => $phone->getName(),
                'quantity' => $phone->getQuantity(),
            ];
        }

        return new JsonResponse($data, Response::HTTP_OK, [], true);
    }
}
