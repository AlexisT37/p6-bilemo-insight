<?php

namespace App\Tests\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    public function testIndexUsers()
    {
        $client = static::createClient();
        $client->request('GET', '/users/test');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertJson($client->getResponse()->getContent());
        $responseData = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('message', $responseData);
        $this->assertArrayHasKey('path', $responseData);
        $this->assertEquals('Welcome to your new user controller, this is a test!', $responseData['message']);
        $this->assertEquals('src/Controller/UserController.php', $responseData['path']);
    }

    public function testGetAllUsers()
    {
        // $this->markTestSkipped('The test to get all users has been skipped.');

        $client = static::createClient();

        $userRepository = static::getContainer()->get(UserRepository::class);

        $testUser = $userRepository->findOneBy(['email' => 'admin@bilemo.com']);

        $client->loginUser($testUser);

        $client->request('GET', '/api/users');
        $this->assertResponseIsSuccessful();
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $this->assertJson($client->getResponse()->getContent());

        $responseData = json_decode($client->getResponse()->getContent(), true);
        $this->assertCount(2, $responseData);

    }


    public function testGetAllUsersWithoutBeingAnAdmin()
    {
        // $this->markTestSkipped('The test to get all users has been skipped.');

        $client = static::createClient();

        $userRepository = static::getContainer()->get(UserRepository::class);

        $testUser = $userRepository->findOneBy(['email' => 'margesimpson@bilemo.com']);

        $client->loginUser($testUser);

        $client->request('GET', '/api/users');

        $this->assertEquals(403, $client->getResponse()->getStatusCode());

    }
}
