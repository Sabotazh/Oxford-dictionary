<?php

namespace App\Tests\Functional\Controller\Auth;

use App\Controller\Auth\SecurityController;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class SecurityControllerTest extends WebTestCase
{
    /**
     * @group functional
     */
    public function testRender(): void
    {
        $client = static::createClient();
        $client->getContainer();

        $client->request('GET', '/login');

        $this->assertResponseIsSuccessful();
    }

    /**
     * @group functional
     */
    public function testLoginUser(): void
    {
        $client = static::createClient();
        /** @var UserRepository $userRepository */
        $userRepository = static::getContainer()->get(UserRepository::class);
        /** @var User $entity */
        $entity = $userRepository->findByRole('User')[1];
        $user = [
            'email' => $entity->getEmail(),
            'password' => 'secret',
        ];

        $client->request('GET', '/login');

        $client->submitForm('Login', $user);

        $this->assertResponseRedirects('/user/profile', 302);
    }

    /**
     * @group functional
     */
    public function testLoginAdmin(): void
    {
        $client = static::createClient();
        /** @var UserRepository $userRepository */
        $userRepository = static::getContainer()->get(UserRepository::class);
        /** @var User $entity */
        $entity = $userRepository->findByRole('Admin')[0];
        $user = [
            'email' => $entity->getEmail(),
            'password' => 'secret',
        ];

        $client->request('GET', '/login');

        $client->submitForm('Login', $user);

        $this->assertResponseRedirects('/admin/dashboard', 302);
    }

    /**
     * @group functional
     */
    public function testIsUserLogined(): void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);

        $user = $userRepository->findByRole('User')[0];
        $client->loginUser($user);

        $client->request('GET', '/login');

        $this->assertResponseRedirects('/user/profile', 302);
    }

    /**
     * @group functional
     */
    public function testLogout(): void
    {
        $client = static::createClient();
        $client->getContainer();

        $client->request('GET', '/logout');

        $this->assertResponseRedirects();
    }

    /**
     * @group functional
     */
    public function testLogoutThrowExecprion(): void
    {
        $security = static::getContainer()->get(SecurityController::class);

        $this->expectException(\LogicException::class);

        $security->logout();
    }
}
