<?php

namespace App\Tests\Unit\Security;

use App\Security\LoginFormAuthenticator;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;

final class LoginFormAuthenticatorTest extends KernelTestCase
{
    /**
     * @group unit
     */
    public function testAuthenticate(): void
    {
        $request = new Request();
        $request->request->set('email', 'test@email.com');
        $request->setSession(new Session(new MockArraySessionStorage()));

        $authenticator = static::getContainer()->get(LoginFormAuthenticator::class);

        $result = $authenticator->authenticate($request);

        $this->assertInstanceOf(Passport::class, $result);
    }
}
