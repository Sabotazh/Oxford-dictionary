<?php

namespace App\Tests\Functional\Repository;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

final class UserRepositoryTest extends KernelTestCase
{
    private UserRepository $userRepository;

    protected function setUp(): void
    {
        $this->userRepository = static::getContainer()->get(UserRepository::class);
    }

    /**
     * @group functional
     */
    public function testRemoveUser(): void
    {
        /** @var User $user*/
        $user = $this->userRepository->findByRole('User')[1];

        $this->userRepository->remove($user, true);

        $removedUser = $this->userRepository->findOneBy(['email' => $user->getEmail()]);

        $this->assertNull($removedUser);
    }

    /**
     * @group functional
     */
    public function testUpgradePassword(): void
    {
        /** @var User $user*/
        $user = $this->userRepository->findByRole('User')[1];
        $newHashedPassword = '$2y$13$pruD8xbAtwwZf6FHHW.I6eAxp01TSZxUGE4hiAYzbaj7NgI088RWW';

        $this->userRepository->upgradePassword($user, $newHashedPassword);

        $this->assertEquals($user->getPassword(), $newHashedPassword);
    }

    /**
     * @group functional
     */
    public function testUpgradePasswordWithWrongUserEntity(): void
    {
        $user = new class implements PasswordAuthenticatedUserInterface {
            public function getPassword(): ?string
            {
                return null;
            }
        };
        $newHashedPassword = '$2y$13$pruD8xbAtwwZf6FHHW.I6eAxp01TSZxUGE4hiAYzbaj7NgI!!!!!!';

        $this->expectException(UnsupportedUserException::class);
        $this->userRepository->upgradePassword($user, $newHashedPassword);
    }
}
