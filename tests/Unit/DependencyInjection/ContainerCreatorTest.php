<?php

namespace Tests\Unit\Kuick\Framework\DependencyInjection;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Depends;
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

    /**
     * @runInSeparateProcess
     */
    public function testIfDefaultEnvIsProd(): void
    {
        putenv('APP_ENV');
        (new Filesystem())->remove(self::$projectDir . '/var/cache');
        $container = (new ContainerCreator())->create(self::$projectDir);
        $this->assertEquals('prod', $container->get('app.env'));
        (new Filesystem())->remove(self::$projectDir . '/var/cache');
    }

    /**
     * @runInSeparateProcess
     */
    public function testIfProdContainerIsBuiltForProd(): void
    {
        putenv('APP_ENV=prod');
        (new Filesystem())->remove(self::$projectDir . '/var/cache');
        $container = (new ContainerCreator())->create(self::$projectDir);
        $this->assertEquals('Testing App', $container->get('app.name'));
        $this->assertEquals('Europe/Paris', $container->get('app.timezone'));
        // Cache is intentionally left in place for the next test
    }

    /**
     * @runInSeparateProcess
     */
    #[Depends('testIfProdContainerIsBuiltForProd')]
    public function testIfProdContainerIsLoadedFromCache(): void
    {
        putenv('APP_ENV=prod');
        (new Filesystem())->remove(self::$projectDir . '/var/cache');
        // Build the cache in a child process to avoid CompiledContainer class redefinition
        $autoloader = realpath(self::$projectDir . '/../../../../vendor/autoload.php');
        $projectDir = self::$projectDir;
        $output = [];
        $exitCode = 0;
        exec(PHP_BINARY . ' -r ' . escapeshellarg(
            "require '{$autoloader}'; putenv('APP_ENV=prod');" .
            "(new Kuick\\Framework\\DependencyInjection\\ContainerCreator())->create('{$projectDir}');"
        ) . ' 2>&1', $output, $exitCode);
        $this->assertEquals(0, $exitCode, 'Cache build subprocess failed: ' . implode(' ', $output));
        $cacheFile = self::$projectDir . '/var/cache/prod/CompiledContainer.php';
        $this->assertFileExists($cacheFile, 'Cache file not created by subprocess');
        $this->assertStringContainsString('app.projectDir', file_get_contents($cacheFile), 'Cache file missing app.projectDir');
        // Also verify the compiled class would have it in METHOD_MAPPING
        // by checking via a separate process
        $checkOutput = [];
        exec(PHP_BINARY . ' -r ' . escapeshellarg(
            "require '{$autoloader}'; require '{$cacheFile}'; " .
            "echo isset(CompiledContainer::METHOD_MAPPING['app.projectDir']) ? 'YES' : 'NO';"
        ), $checkOutput);
        $this->assertEquals('YES', implode('', $checkOutput), 'app.projectDir not in METHOD_MAPPING');
        // Load from cache — triggers the cached container path
        $container = (new ContainerCreator())->create(self::$projectDir);
        $this->assertEquals('Testing App', $container->get('app.name'));
        $this->assertEquals('Europe/Paris', $container->get('app.timezone'));
        (new Filesystem())->remove(self::$projectDir . '/var/cache');
    }
}
