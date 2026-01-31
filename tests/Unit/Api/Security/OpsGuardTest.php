<?php

namespace Tests\Unit\Kuick\Framework\Api\Security;

use Kuick\Framework\Api\Security\OpsGuard;
use Kuick\Http\HttpException;
use Nyholm\Psr7\ServerRequest;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(OpsGuard::class)]
class OpsGuardTest extends TestCase
{
    public function testIfQuitsGracefullyGivenAValidToken(): void
    {
        $guard = new OpsGuard('let-me-in');
        $request = (new ServerRequest('GET', '/'))
            ->withAddedHeader('Authorization', 'Bearer let-me-in');
        $this->expectNotToPerformAssertions();
        $guard($request);
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
