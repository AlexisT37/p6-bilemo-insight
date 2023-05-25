<?php

namespace App\Tests\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

// use advanced test case


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

        $testUser = $userRepository->findOneBy(['email' => 'margesimpson@bilemo.com']);

        $client->loginUser($testUser);

        $client->request('GET', '/api/users');
        $this->assertResponseIsSuccessful();
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $this->assertJson($client->getResponse()->getContent());

        $responseData = json_decode($client->getResponse()->getContent(), true);
        $this->assertCount(2, $responseData);

        // $brands = ['Apple', 'Samsung', 'Huawei', 'Xiaomi', 'Oppo', 'Vivo'];
        // $models = ['A', 'B', 'C', 'D', 'E', 'F'];

        // foreach ($responseData as $phone) {
        //     $this->assertArrayHasKey('id', $phone);
        //     $this->assertArrayHasKey('name', $phone);
        //     $this->assertArrayHasKey('quantity', $phone);
        //     $this->assertArrayHasKey('model', $phone);
        //     $this->assertArrayHasKey('brand', $phone);

        //     $this->assertGreaterThanOrEqual(0, $phone['quantity']);
        //     $this->assertLessThanOrEqual(100, $phone['quantity']);
        //     $this->assertContains($phone['brand'], $brands);
        //     $this->assertContains($phone['model'], $models);
        // }
    }
}
