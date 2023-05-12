<?php

namespace App\Tests\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApiLoginCheckTest extends WebTestCase
{



    public function testLoginCheck(): void
    {
        $client = static::createClient();

        $userRepository = static::getContainer()->get(UserRepository::class);

        $testUser = $userRepository->findOneBy(['email' => 'margesimpson@bilemo.com']);

        $client->loginUser($testUser);

        $client->request('GET', '/api/phone');

        $this->assertResponseIsSuccessful();
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $token = static::getContainer()->get('security.token_storage')->getToken();
        $this->assertEquals($testUser, $token->getUser());
    }
}
