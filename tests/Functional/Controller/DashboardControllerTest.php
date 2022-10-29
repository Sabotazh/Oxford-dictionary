<?php

namespace App\Tests\Functional\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DashboardControllerTest extends WebTestCase
{
    /**
     * @group functional
     */
    public function testRender()
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);

        $admin = $userRepository->findByRole('Admin')[0];
        $client->loginUser($admin);

        $client->request('GET', '/admin/dashboard');

        $this->assertResponseIsSuccessful();
    }
}
