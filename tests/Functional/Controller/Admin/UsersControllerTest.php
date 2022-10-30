<?php

namespace App\Tests\Functional\Controller\Admin;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class UsersControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private UserRepository $userRepository;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->userRepository = static::getContainer()->get(UserRepository::class);
    }

    /**
     * @group functional
     */
    public function testRender(): void
    {
        $this->loginAdmin();

        $this->client->request('GET', '/admin/users');

        $this->assertResponseIsSuccessful();
    }

    /**
     * @group functional
     */
    public function testBannUser(): void
    {
        $this->loginAdmin();

        $user = $this->userRepository->findOneBy(['isBanned' => false]);
        $this->assertFalse($user->isBanned());

        $this->client->request('GET', sprintf('/admin/users/user/%s/bann', $user->getId()));
        $this->assertResponseRedirects('/admin/users', 302);
        $this->assertTrue($user->isBanned());
    }

    /**
     * @group functional
     */
    public function testBannUserException(): void
    {
        $this->loginAdmin();

        $this->client->request('GET', sprintf('/admin/users/user/%s/bann', 0));
        $this->assertResponseRedirects('/admin/users', 302);
    }

    /**
     * @group functional
     */
    public function testUnbannUserException(): void
    {
        $this->loginAdmin();

        $this->client->request('GET', sprintf('/admin/users/user/%s/unbann', 0));
        $this->assertResponseRedirects('/admin/users', 302);
    }

    /**
     * @group functional
     */
    public function testUnbannUser(): void
    {
        $this->loginAdmin();

        $user = $this->userRepository->findOneBy(['isBanned' => true]);
        $this->assertTrue($user->isBanned());

        $this->client->request('GET', sprintf('/admin/users/user/%s/unbann', $user->getId()));
        $this->assertResponseRedirects('/admin/users', 302);
        $this->assertFalse($user->isBanned());
    }

    private function loginAdmin(): void
    {
        $admin = $this->userRepository->findByRole('Admin')[0];
        $this->client->loginUser($admin);
    }
}
