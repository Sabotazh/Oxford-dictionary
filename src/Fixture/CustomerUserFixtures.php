<?php

namespace App\Fixture;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class CustomerUserFixtures extends Fixture
{
    private UserRepository $repository;
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserRepository $repository, UserPasswordHasherInterface $hasher)
    {
        $this->repository = $repository;
        $this->hasher = $hasher;
    }

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setEmail('user@mail.com')
            ->setName('User')
            ->setRoles(['ROLE_USER'])
            ->setPassword(
                $this->hasher->hashPassword($user, 'secret')
            );

        $this->repository->save($user, true);
    }
}
