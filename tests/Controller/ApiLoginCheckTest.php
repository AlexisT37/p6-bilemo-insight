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

        
        $this->assertJson($client->getResponse()->getContent());

        $responseData = json_decode($client->getResponse()->getContent(), true);
        $this->assertCount(5, $responseData);

        $brands = ['Apple', 'Samsung', 'Huawei', 'Xiaomi', 'Oppo', 'Vivo'];
        $models = ['A', 'B', 'C', 'D', 'E', 'F'];

        foreach ($responseData as $phone) {
            $this->assertArrayHasKey('id', $phone);
            $this->assertArrayHasKey('name', $phone);
            $this->assertArrayHasKey('quantity', $phone);
            $this->assertArrayHasKey('model', $phone);
            $this->assertArrayHasKey('brand', $phone);

            $this->assertGreaterThanOrEqual(0, $phone['quantity']);
            $this->assertLessThanOrEqual(100, $phone['quantity']);
            $this->assertContains($phone['brand'], $brands);
            $this->assertContains($phone['model'], $models);
        }

    }
}
