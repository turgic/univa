<?php

namespace App\Tests\Controller\Api;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class UserControllerTest extends WebTestCase
{
    public function testNewUserWithValidDataByAdmin(): void
    {
        $client = static::createClient();

        $this->authenticateUser($client);

        $client->request(
            'POST',
            '/api/users',
            [],
            [],
            [],
            json_encode([
                'email' => 'test@example.com',
                'password' => 'password',
                'role' => 'ROLE_USER',
            ])
        );

        $response = $client->getResponse();
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
    }

    public function testNewUserWithValidDataByUser(): void
    {
    }

    public function testEditUserWithValidDataByAdmin(): void
    {
    }

    public function testEditUserWithValidDataByUser(): void
    {
    }

    private function authenticateUser(KernelBrowser $client): void
    {
        // Make a request to authenticate
        $client->request(
            'POST',
            '/api/login_check',
            [],
            [],
            [],
            json_encode([
                'username' => 'user@mail.com',
                'password' => 'user',
            ])
        );
        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    }
}
