<?php

namespace Tests\Unit\Kuick\Framework\Config;

use Kuick\Framework\Config\GuardConfig;
use PHPUnit\Framework\Attributes\CoversClass;
use Tests\Unit\Kuick\Framework\Mocks\MockGuard;
use PHPUnit\Framework\TestCase;

#[CoversClass(GuardConfig::class)]
class GuardConfigTest extends TestCase
{
    public function testIfGuardConfigIsDefinedWithTheDefaultMethods(): void
    {
        $guardConfig = new GuardConfig('/test', MockGuard::class);
        $this->assertEquals('/test', $guardConfig->path);
        $this->assertEquals(MockGuard::class, $guardConfig->guardClassName);
        $this->assertEquals([
            'GET',
            'OPTIONS',
            'POST',
            'PUT',
            'PATCH',
            'DELETE',
        ], $guardConfig->methods);
    }
}
