<?php

namespace App\Tests\Unit\Security;

use App\Security\AccessDeniedHandler;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

final class AccessDeniedHandlerTest extends TestCase
{
    /**
     * @group unit
     */
    public function testHandle(): void
    {
        $request = \Mockery::mock(Request::class);
        $exception = \Mockery::mock(AccessDeniedException::class);

        $handler = new AccessDeniedHandler();
        $result = $handler->handle($request, $exception);

        $this->assertEquals('403', $result->getStatusCode());
        $this->assertEquals('Access Denied', $result->getContent());
    }
}
