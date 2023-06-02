<?php

namespace App\Controller;

use App\Repository\CustomerRepository;
use JMS\Serializer\SerializerInterface;
use JMS\Serializer\SerializationContext;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class CustomerController extends AbstractController
{
    private $userPasswordHasher;
    
    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userPasswordHasher = $userPasswordHasher;
    }

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

        $context = SerializationContext::create()->setGroups(['getCustomers']);

        // extract the page number and limit from url parameters
        $page = $request->query->get('page', 1);
        $limit = $request->query->get('limit', 10);

        // get the current logged in user
        // The potential intellephense error is not an error, it is a bug in the intellephense extension that falsely interpret the user as the UserInterface but it is the User entity which indeed has the getId() method
        $user = $this->getUser()->getId();

        // use the function findAllWithPaginationForCurrentClient() to get all the customers for the current user
        $customers = $customerRepository->findAllWithPaginationForCurrentClient($page, $limit, $user);

        $jsonClients = $serializer->serialize($customers, 'json', $context);

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

        $context = SerializationContext::create()->setGroups(['getCustomer']);
        // get the current logged in user
        // The potential intellephense error is not an error, it is a bug in the intellephense extension that falsely interpret the user as the UserInterface but it is the User entity which indeed has the getId() method
        $user = $this->getUser()->getId();


        // use the function findOneByIdForCurrentClient() to get the customer for the current user
        $customer = $customerRepository->findOneByIdForCurrentClient($id, $user);

        

        // if $customer is null, return a 403 forbidden response
        if ($customer === null) {
            // return a not found response with a message that the customer was not found or deleted
            return new JsonResponse(['message' => 'Customer not found, either it is not yours or it was deleted'], Response::HTTP_NOT_FOUND);
        }

        $jsonCustomer = $serializer->serialize($customer, 'json', $context);

        return new JsonResponse($jsonCustomer, Response::HTTP_OK, [], true);
    }

    // Function to create a new customer, only accessible by a logged in client
    #[Route('/api/customers', name: 'app_customers_create', methods: ['POST'])]
    public function createCustomer(Request $request, CustomerRepository $customerRepository, SerializerInterface $serializer): JsonResponse
    {
        // Check if the current user has admin privileges
        if (!$this->isGranted('ROLE_USER')) {
            // Throw an access denied exception
            // throw new AccessDeniedException('Unable to access this page, you are not an admin!');
            return new JsonResponse(['message' => 'Unable to access this page, you are not a client!'], Response::HTTP_FORBIDDEN);
    
        }


        $context = SerializationContext::create()->setGroups(['getCustomer']);

        // get the current logged in user
        // The potential intellephense error is not an error, it is a bug in the intellephense extension that falsely interpret the user as the UserInterface but it is the User entity which indeed has the getId() method
        // For the creation, we use the user, not the id, because the user entity is used in the function createCustomer() in the CustomerRepository
        // Intellephense is still not happy so we use an annotation to tell it that the user is an entity
        /** @var \App\Entity\User $user */
        $user = $this->getUser();

        // get the data from the request
        $data = json_decode($request->getContent(), true);

        // get the email from the data

        $email = $data['email'];
        $password = $data['password'];

        $hashedPassword = $this->userPasswordHasher->hashPassword($user, $password);

        // create a new customer
        $customer = $customerRepository->createCustomer($email, $hashedPassword, $user);

        $jsonCustomer = $serializer->serialize($customer, 'json', $context);

        return new JsonResponse($jsonCustomer, Response::HTTP_CREATED, [], true);
    }

    // Function to update a customer, only accessible by a logged in client
    #[Route('/api/customers/{id}', name: 'app_customers_update', methods: ['PUT'])]
    public function updateCustomer(Request $request, CustomerRepository $customerRepository, SerializerInterface $serializer, $id): JsonResponse
    {
        // Check if the current user has admin privileges
        if (!$this->isGranted('ROLE_USER')) {
            return new JsonResponse(['message' => 'Unable to access this page, you are not a client!'], Response::HTTP_FORBIDDEN);
        }

        $context = SerializationContext::create()->setGroups(['getCustomer']);

        // get the current logged in user
        // The potential intellephense error is not an error, it is a bug in the intellephense extension that falsely interpret the user as the UserInterface but it is the User entity which indeed has the getId() method
        // For the creation, we use the user, not the id, because the user entity is used in the function createCustomer() in the CustomerRepository
        // Intellephense is still not happy so we use an annotation to tell it that the user is an entity
        /** @var \App\Entity\User $user */
        $user = $this->getUser();

        // use the function findOneByIdForCurrentClient() to get the customer for the current user
        $customer = $customerRepository->findOneByIdForCurrentClient($id, $user);

        // if $customer is null, return a 403 forbidden response
        if ($customer === null) {
            return new JsonResponse(['message' => 'Unable to access this page, you are not the owner of this customer!'], Response::HTTP_FORBIDDEN);
        }

        // get the data from the request
        $data = json_decode($request->getContent(), true);

        $hashedPassword = $this->userPasswordHasher->hashPassword($user, $data['password']);

        // update the customer with the new data
        $customer->setEmail($data['email']);
        $customer->setPassword($hashedPassword);

        // save the updated customer to the database
        $customerRepository->save($customer, true);

        $jsonCustomer = $serializer->serialize($customer, 'json', $context);

        return new JsonResponse($jsonCustomer, Response::HTTP_OK, [], true);
    }

    // Function to delete a customer, only accessible by a logged in client
    #[Route('/api/customers/{id}', name: 'app_customers_delete', methods: ['DELETE'])]
    public function deleteCustomer(Request $request, CustomerRepository $customerRepository, SerializerInterface $serializer, $id): JsonResponse
    {
        // Check if the current user has admin privileges
        if (!$this->isGranted('ROLE_USER')) {
            return new JsonResponse(['message' => 'Unable to access this page, you are not a client!'], Response::HTTP_FORBIDDEN);
        }

        // get the current logged in user
        // The potential intellephense error is not an error, it is a bug in the intellephense extension that falsely interpret the user as the UserInterface but it is the User entity which indeed has the getId() method
        // For the creation, we use the user, not the id, because the user entity is used in the function createCustomer() in the CustomerRepository
        // Intellephense is still not happy so we use an annotation to tell it that the user is an entity
        /** @var \App\Entity\User $user */
        $user = $this->getUser();

        // use the function findOneByIdForCurrentClient() to get the customer for the current user
        $customer = $customerRepository->findOneByIdForCurrentClient($id, $user);

        // if $customer is null, return a 403 forbidden response
        if ($customer === null) {
            return new JsonResponse(['message' => 'Unable to access this page, you are not the owner of this customer!'], Response::HTTP_FORBIDDEN);
        }

        // delete the customer
        $customerRepository->remove($customer, true);

        return new JsonResponse(['message' => 'Customer deleted'], Response::HTTP_OK);

    }
}
