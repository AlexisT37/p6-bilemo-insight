<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApiLoginCheckTest extends WebTestCase
{
    public function testLoginCheck(): void
    {
        $client = static::createClient();

        // Attempt to login
        $crawler = $client->request('POST', '/api/login_check', [
            'username' => 'margesimpson@bilemo.com',
            'password' => 'MargeSimpson3224#',
        ]);

        // Assert response contains token
        $response = $client->getResponse();
        $responseData = json_decode($response->getContent(), true);
        dump($responseData);
        // dump status code
        dump($response->getStatusCode());
        $this->assertEquals(200, $response->getStatusCode());
        // $this->assertArrayHasKey('token', $responseData);

        // // Store the token
        // $token = $responseData['token'];

        // // Perform authenticated request
        // $crawler = $client->request('GET', '/api/phone', [], [], [
        //     'HTTP_Authorization' => 'Bearer '.$token,
        // ]);

        // // Assert the response status code
        // $this->assertEquals(200, $client->getResponse()->getStatusCode());

        // // Perform unauthenticated request
        // $crawler = $client->request('GET', '/api/phone');

        // // Assert the response status code
        // $this->assertEquals(401, $client->getResponse()->getStatusCode());
    }
}
