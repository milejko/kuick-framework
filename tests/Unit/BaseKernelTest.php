<?php

namespace Tests\Unit\Kuick\Framework;

use Kuick\Framework\BaseKernel;
use Kuick\Framework\KernelInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Container\ContainerInterface;
use Symfony\Component\Filesystem\Filesystem;

#[CoversClass(BaseKernel::class)]
class BaseKernelTest extends TestCase
{
    public static string $projectDir;

    public static function setUpBeforeClass(): void
    {
        self::$projectDir = realpath(__DIR__ . '/Mocks/project-dir');
    }

    public function testIfDevKernelIsWellDefined(): void
    {
        putenv('APP_ENV=dev');
        $kernel = new BaseKernel(self::$projectDir);
        $this->assertInstanceOf(KernelInterface::class, $kernel);
        $this->assertInstanceOf(ContainerInterface::class, $container = $kernel->getContainer());
        $this->assertInstanceOf(EventDispatcherInterface::class, $container->get(EventDispatcherInterface::class));
        $this->assertEquals('Testing App', $container->get('app.name'));
        $this->assertEquals('dev', $container->get('app.env'));
        $this->assertEquals('Europe/Warsaw', $container->get('app.timezone'));
    }

    public function testIfTestKernelIsWellDefined(): void
    {
        putenv('APP_ENV=test');
        $kernel = new BaseKernel(self::$projectDir);
        $this->assertInstanceOf(KernelInterface::class, $kernel);
        $this->assertInstanceOf(ContainerInterface::class, $container = $kernel->getContainer());
        $this->assertInstanceOf(EventDispatcherInterface::class, $container->get(EventDispatcherInterface::class));
        $this->assertEquals('test', $container->get('app.env'));
        $this->assertEquals('Europe/London', $container->get('app.timezone'));
        (new Filesystem())->remove(self::$projectDir . '/var/cache');
    }
}
