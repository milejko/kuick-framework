<?php

namespace Tests\Unit\Kuick\Framework;

use Kuick\Cache\LayeredCache;
use Kuick\Framework\SystemCache;
use Kuick\Framework\SystemCacheInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(SystemCache::class)]
class SystemCacheTest extends TestCase
{
    public function testIfProdCacheServiceIsWellDefined(): void
    {
        $cache = new SystemCache(__DIR__ . '/Mocks/project-dir', 'prod');
        $this->assertInstanceOf(LayeredCache::class, $cache);
        $this->assertInstanceOf(SystemCacheInterface::class, $cache);
    }

    public function testIfDevCacheIsCreated(): void
    {
        $cache = new SystemCache(dirname(__DIR__) . 'even-inexistent-dir-will-work', 'dev');
        $this->assertInstanceOf(LayeredCache::class, $cache);
        $this->assertInstanceOf(SystemCacheInterface::class, $cache);
    }

    public function testIfProdCacheCanSetAndGet(): void
    {
        $cache = new SystemCache(__DIR__ . '/Mocks/project-dir', 'prod');
        $cache->set('test-key', 'test-value');
        $this->assertSame('test-value', $cache->get('test-key'));
    }

    public function testIfDevCacheReturnsNullForAnyKey(): void
    {
        $cache = new SystemCache(dirname(__DIR__) . 'even-inexistent-dir-will-work', 'dev');
        $this->assertNull($cache->get('any-key'));
    }

    public function testIfApcuLayerIsAddedWhenAvailable(): void
    {
        if (!function_exists('apcu_enabled') || !apcu_enabled()) {
            $this->markTestSkipped('APCu extension is not available or not enabled');
        }
        $cache = new SystemCache(__DIR__ . '/Mocks/project-dir', 'prod');
        $this->assertInstanceOf(LayeredCache::class, $cache);
        $cache->set('apcu-test-key', 'apcu-test-value');
        $this->assertSame('apcu-test-value', $cache->get('apcu-test-key'));
    }
}
