<?php

namespace Tests\Unit\Kuick\Framework\DependencyInjection;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunInSeparateProcess;
use PHPUnit\Framework\TestCase;
use Kuick\Framework\DependencyInjection\ContainerCreator;
use Symfony\Component\Filesystem\Filesystem;

#[CoversClass(ContainerCreator::class)]
class ContainerCreatorTest extends TestCase
{
    private static string $projectDir;

    public static function setUpBeforeClass(): void
    {
        self::$projectDir = realpath(dirname(__DIR__) . '/Mocks/project-dir');
    }

    public function testIfDevContainerIsBuiltForDev(): void
    {
        putenv('APP_ENV=dev');
        $container = (new ContainerCreator())->create(self::$projectDir);
        $this->assertEquals('Testing App', $container->get('app.name'));
        $this->assertEquals('dev', $container->get('app.env'));
        $this->assertEquals(self::$projectDir, $container->get('app.projectDir'));
        $this->assertEquals('Europe/Warsaw', $container->get('app.timezone'));
    }

    public function testIfDefaultEnvIsProd(): void
    {
        putenv('APP_ENV');
        (new Filesystem())->remove(self::$projectDir . '/var/cache');
        $container = (new ContainerCreator())->create(self::$projectDir);
        $this->assertEquals('prod', $container->get('app.env'));
        (new Filesystem())->remove(self::$projectDir . '/var/cache');
    }

    public function testIfProdContainerIsBuiltForProd(): void
    {
        putenv('APP_ENV=prod');
        (new Filesystem())->remove(self::$projectDir . '/var/cache');
        $container = (new ContainerCreator())->create(self::$projectDir);
        $this->assertEquals('Testing App', $container->get('app.name'));
        $this->assertEquals('Europe/Paris', $container->get('app.timezone'));
        // Cache is intentionally left in place for the next test
    }

    #[RunInSeparateProcess]
    public function testIfProdContainerIsLoadedFromCache(): void
    {
        putenv('APP_ENV=prod');
        (new Filesystem())->remove(self::$projectDir . '/var/cache');
        // Build the cache in a child process to avoid CompiledContainer class redefinition
        $autoloader = realpath(self::$projectDir . '/../../../../vendor/autoload.php');
        $projectDir = self::$projectDir;
        exec(PHP_BINARY . ' -r ' . escapeshellarg(
            "require '{$autoloader}'; putenv('APP_ENV=prod');" .
            "(new Kuick\\Framework\\DependencyInjection\\ContainerCreator())->create('{$projectDir}');"
        ) . ' 2>/dev/null');
        // Load from cache — triggers the cached container path
        $container = (new ContainerCreator())->create(self::$projectDir);
        $this->assertEquals('Testing App', $container->get('app.name'));
        $this->assertEquals('Europe/Paris', $container->get('app.timezone'));
        (new Filesystem())->remove(self::$projectDir . '/var/cache');
    }
}
