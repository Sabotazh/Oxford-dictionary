<?php

namespace App\Tests\Functional\Controller\Auth;

use App\Repository\UserRepository;
use Faker\Factory as Faker;
use Faker\Generator;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class RegistrationControllerTest extends WebTestCase
{
    private Generator $faker;
    private array $user;

    protected function setUp(): void
    {
        $this->faker = Faker::create();
        $this->setUserData();
    }

    /**
     * @group functional
     */
    public function testRender(): void
    {
        $client = static::createClient();
        $client->getContainer();

        $client->request('GET', '/registration');

        $this->assertResponseIsSuccessful();
    }

    /**
     * @group functional
     */
    public function testRegistrationSubmission(): void
    {
        $client = static::createClient();
        $client->request('GET', '/registration');

        $client->submitForm('Register', $this->user);

        $this->assertResponseRedirects('/login', 302);
    }

    /**
     * @group functional
     */
    public function testFailedRegistrationSubmission(): void
    {
        $client = static::createClient();
        /** @var UserRepository $userRepository */
        $userRepository = static::getContainer()->get(UserRepository::class);
        $email = $userRepository->find(mt_rand(1, count($userRepository->findAll())))->getEmail();
        $this->user['email'] = $email;

        $client->request('GET', '/registration');

        $client->submitForm('Register', $this->user);

        $this->assertResponseRedirects('/registration', 302);
    }

    private function setUserData(): void
    {
        $this->user = [
            'email' => $this->faker->unique()->email(),
            'name' => $this->faker->name(),
            'password[first]' => $password = $this->faker->password(8),
            'password[second]' => $password,
        ];
    }
}
