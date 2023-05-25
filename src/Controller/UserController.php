<?php

namespace App\Controller;

use Symfony\Component\Serializer\SerializerInterface;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class UserController extends AbstractController
{
    #[Route('/users/test', name: 'app_users_test', methods: ['GET'])]
    public function index(): JsonResponse
    {
        return new JsonResponse([
            'message' => 'Welcome to your new user controller, this is a test!',
            'path' => 'src/Controller/UserController.php',
        ]);
    }

    // Function to get all the users, only accessible by the admin
    #[Route('/api/users', name: 'app_users', methods: ['GET'])]
    public function getUsers(Request $request, UserRepository $userRepository, SerializerInterface $serializer): JsonResponse
    {
        // Check if the current user has admin privileges
        if (!$this->isGranted('ROLE_ADMIN')) {
            // Throw an access denied exception
            // throw new AccessDeniedException('Unable to access this page, you are not an admin!');
            return new JsonResponse(['message' => 'Unable to access this page, you are not an admin!'], Response::HTTP_FORBIDDEN);
    
        }

        $users = $userRepository->findAllWithPagination(1, 5);

        $jsonUsers = $serializer->serialize($users, 'json');

        return new JsonResponse($jsonUsers, Response::HTTP_OK, [], true);
    }
}
