<?php

namespace App\Controller;

use App\Repository\CustomerRepository;
use Symfony\Component\Serializer\SerializerInterface;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CustomerController extends AbstractController
{
    #[Route('/customers/test', name: 'app_customers_test', methods: ['GET'])]
    public function index(): JsonResponse
    {
        return new JsonResponse([
            'message' => 'Welcome to your new customer controller, this is a test!',
            'path' => 'src/Controller/CustomerController.php',
        ]);
    }

    // Function to get all the users, which are the real users of the api, only accessible by the admin
    #[Route('/api/customers', name: 'app_customers', methods: ['GET'])]
    public function getCustomers(Request $request, CustomerRepository $customerRepository, SerializerInterface $serializer): JsonResponse
    {
        // Check if the current user has admin privileges
        if (!$this->isGranted('ROLE_USER')) {
            // Throw an access denied exception
            // throw new AccessDeniedException('Unable to access this page, you are not an admin!');
            return new JsonResponse(['message' => 'Unable to access this page, you are not a client!'], Response::HTTP_FORBIDDEN);
    
        }

        // get the current logged in user
        // The potential intellephense error is not an error, it is a bug in the intellephense extension that falsely interpret the user as the UserInterface but it is the User entity which indeed has the getId() method
        $user = $this->getUser()->getId();

        // use the function findAllWithPaginationForCurrentClient() to get all the customers for the current user
        $customers = $customerRepository->findAllWithPaginationForCurrentClient(1, 5, $user);

        // $jsonClients = $serializer->serialize($customers, 'json');
        $jsonClients = $serializer->serialize($customers, 'json', ['groups' => 'getCustomers']);

        // $jsonBookList = $serializer->serialize($bookList, 'json', ['groups' => 'getBooks']);

        return new JsonResponse($jsonClients, Response::HTTP_OK, [], true);
    }

    // Function to get a specific user, only accessible by a logged in client
    #[Route('/api/customers/{id}', name: 'app_customers_id', methods: ['GET'])]
    public function getCustomer(Request $request, CustomerRepository $customerRepository, SerializerInterface $serializer, $id): JsonResponse
    {
        // Check if the current user has admin privileges
        if (!$this->isGranted('ROLE_USER')) {
            // Throw an access denied exception
            // throw new AccessDeniedException('Unable to access this page, you are not an admin!');
            return new JsonResponse(['message' => 'Unable to access this page, you are not a client!'], Response::HTTP_FORBIDDEN);
    
        }

        // get the current logged in user
        // The potential intellephense error is not an error, it is a bug in the intellephense extension that falsely interpret the user as the UserInterface but it is the User entity which indeed has the getId() method
        $user = $this->getUser()->getId();


        // use the function findOneByIdForCurrentClient() to get the customer for the current user
        $customer = $customerRepository->findOneByIdForCurrentClient($id, $user);

        // if $customer is null, return a 403 forbidden response
        if ($customer === null) {
            return new JsonResponse(['message' => 'Unable to access this page, you are not the owner of this customer!'], Response::HTTP_FORBIDDEN);
        }

        // $jsonClient = $serializer->serialize($customer, 'json');
        $jsonCustomer = $serializer->serialize($customer, 'json', ['groups' => 'getCustomer']);

        // $jsonBookList = $serializer->serialize($bookList, 'json', ['groups' => 'getBooks']);

        return new JsonResponse($jsonCustomer, Response::HTTP_OK, [], true);
    }
}
