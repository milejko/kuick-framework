<?php

namespace Tests\Unit\Kuick\Framework;

use Kuick\Framework\ConsoleKernel;
use Kuick\Framework\KernelInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Application;
use Symfony\Component\Filesystem\Filesystem;

#[CoversClass(ConsoleKernel::class)]
class ConsoleKernelTest extends TestCase
{
    public static string $projectDir;

    public static function setUpBeforeClass(): void
    {
        self::$projectDir = realpath(__DIR__ . '/Mocks/project-dir');
    }

    public function testIfDevKernelIsWellDefined(): void
    {
        putenv('APP_ENV=dev');
        $kernel = new ConsoleKernel(self::$projectDir);
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
        $kernel = new ConsoleKernel(self::$projectDir);
        $this->assertInstanceOf(KernelInterface::class, $kernel);
        $this->assertInstanceOf(ContainerInterface::class, $container = $kernel->getContainer());
        $this->assertInstanceOf(EventDispatcherInterface::class, $container->get(EventDispatcherInterface::class));
        $this->assertEquals('test', $container->get('app.env'));
        $this->assertEquals('Europe/London', $container->get('app.timezone'));
        (new Filesystem())->remove(self::$projectDir . '/var/cache');
    }

    public function testRun(): void
    {
        putenv('APP_ENV=test');
        $kernel = new ConsoleKernel(self::$projectDir);

        $mockApplication = $this->createMock(Application::class);
        $mockApplication->expects($this->once())
            ->method('run')
            ->willReturn(0);

        $container = $kernel->getContainer();
        /** @phpstan-ignore method.notFound */
        $container->set(Application::class, $mockApplication);

        $exitCode = $kernel->run();
        $this->assertEquals(0, $exitCode);
    }
}
