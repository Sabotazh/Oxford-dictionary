<?php

namespace App\Tests\Unit\Entity;

use App\Entity\User;
use PHPUnit\Framework\TestCase;

final class UserTest extends TestCase
{
    /**
     * @group unit
     */
    public function testRandomUser(): void
    {
        $user = new User();
        $user
            ->setName('Test User')
            ->setEmail('test_user@mail.com')
            ->setPassword('$2y$13$pruD8xbAtwwZf6FHHW.I6eAxp01TSZxUGE4hiAYzbaj7NgI088RWW')
            ->setRoles([User::ROLE_USER])
            ->setCreatedAt()
            ->setUpdatedAt()
            ->setDeleteddAt();

        $this->assertEquals('Test User', $user->getName());
        $this->assertEquals('test_user@mail.com', $user->getEmail());
        $this->assertEquals('$2y$13$pruD8xbAtwwZf6FHHW.I6eAxp01TSZxUGE4hiAYzbaj7NgI088RWW', $user->getPassword());
        $this->assertEquals([User::ROLE_USER], $user->getRoles());
        $this->assertInstanceOf(\DateTime::class, $user->getCreatedAt());
        $this->assertInstanceOf(\DateTime::class, $user->getUpdatedAt());
        $this->assertInstanceOf(\DateTime::class, $user->getDeletedAt());
    }
}
