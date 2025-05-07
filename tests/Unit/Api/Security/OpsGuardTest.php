<?php

namespace Tests\Unit\Kuick\Framework\Api\Security;

use Kuick\Framework\Api\Security\OpsGuard;
use Kuick\Http\HttpException;
use Nyholm\Psr7\ServerRequest;
use PHPUnit\Framework\TestCase;

/**
 * @covers Kuick\Framework\Api\Security\OpsGuard
 */
class OpsGuardTest extends TestCase
{
    public function testIfQuitsGracefullyGivenAValidToken(): void
    {
        $guard = new OpsGuard('let-me-in');
        $request = (new ServerRequest('GET', '/'))
            ->withAddedHeader('Authorization', 'Bearer let-me-in');
        $guard($request);
        $this->assertTrue(true);
    }

    public function testIfMissingTokenThrowsUnauthorized(): void
    {
        $guard = new OpsGuard('let-me-in');
        $request = new ServerRequest('GET', '/');
        $this->expectException(HttpException::class);
        $this->expectExceptionMessage('Token not found');
        $guard($request);
    }

    public function testIfInvalidTokenThrowsForbidden(): void
    {
        $guard = new OpsGuard('let-me-in');
        $request = (new ServerRequest('GET', '/'))
            ->withAddedHeader('Authorization', 'Bearer invalid-token');
        $this->expectException(HttpException::class);
        $this->expectExceptionMessage('Token invalid');
        $guard($request);
    }
}
