<?php

namespace App\Controller;

use Symfony\Component\Serializer\SerializerInterface;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ClientController extends AbstractController
{
    #[Route('/clients/test', name: 'app_clients_test', methods: ['GET'])]
    public function index(): JsonResponse
    {
        return new JsonResponse([
            'message' => 'Welcome to your new client controller, this is a test!',
            'path' => 'src/Controller/ClientController.php',
        ]);
    }

    // Function to get all the users, which are the real users of the api, only accessible by the admin
    #[Route('/api/clients', name: 'app_clients', methods: ['GET'])]
    public function getClients(Request $request, UserRepository $clientRepository, SerializerInterface $serializer): JsonResponse
    {
        // Check if the current user has admin privileges
        if (!$this->isGranted('ROLE_ADMIN')) {
            // Throw an access denied exception
            // throw new AccessDeniedException('Unable to access this page, you are not an admin!');
            return new JsonResponse(['message' => 'Unable to access this page, you are not an admin!'], Response::HTTP_FORBIDDEN);
    
        }

        $clients = $clientRepository->findAllWithPagination(1, 5);

        $jsonClients = $serializer->serialize($clients, 'json');

        return new JsonResponse($jsonClients, Response::HTTP_OK, [], true);
    }
}
