<?php

namespace Tests\Unit\Kuick\Framework\Config;

use Kuick\Framework\Config\MiddlewareConfig;
use Kuick\Routing\RoutingMiddleware;
use Kuick\Security\SecurityMiddleware;
use PHPUnit\Framework\TestCase;

/**
 * @covers Kuick\Framework\Config\MiddlewareConfig
 */
class MiddlewareConfigTest extends TestCase
{
    public function testIfMiddlewareConfigIsDefinedWithTheDefaultMethods(): void
    {
        $middlewareConfig = new MiddlewareConfig(SecurityMiddleware::class);
        $this->assertEquals(SecurityMiddleware::class, $middlewareConfig->middlewareClassName);
        $this->assertNull($middlewareConfig->beforeMiddlewareClassName);
        $anotherConfig = new MiddlewareConfig(RoutingMiddleware::class, SecurityMiddleware::class);
        $this->assertEquals(RoutingMiddleware::class, $anotherConfig->middlewareClassName);
        $this->assertEquals(SecurityMiddleware::class, $anotherConfig->beforeMiddlewareClassName);
    }
}
