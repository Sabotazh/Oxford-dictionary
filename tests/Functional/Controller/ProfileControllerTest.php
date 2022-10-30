<?php

namespace App\Tests\Functional\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProfileControllerTest extends WebTestCase
{
    /**
     * @group functional
     */
    public function testRender()
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);

        $user = $userRepository->findByRole('User')[0];
        $client->loginUser($user);

        $client->request('GET', '/user/profile');

        $this->assertResponseIsSuccessful();
    }
}
