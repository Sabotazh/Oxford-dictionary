<?php

namespace App\Fixture;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory as Faker;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private UserRepository $repository;
    private UserPasswordHasherInterface $hasher;
    private $faker;

    public function __construct(
        UserRepository $repository,
        UserPasswordHasherInterface $hasher,
        Faker $faker
    )
    {
        $this->repository = $repository;
        $this->hasher = $hasher;
        $this->faker = $faker::create();
    }

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 100; ++$i) {
            $user = new User();
            $user->setEmail($this->faker->unique()->email())
                ->setName($this->faker->name())
                ->setRoles([User::ROLE_USER])
                ->setPassword(
                    '$2y$13$pruD8xbAtwwZf6FHHW.I6eAxp01TSZxUGE4hiAYzbaj7NgI088RWW'
                )
                ->setIsBanned($this->faker->boolean())
                ->setCreatedAt()
                ->setUpdatedAt();

            $this->repository->save($user, true);
        }
    }
}
