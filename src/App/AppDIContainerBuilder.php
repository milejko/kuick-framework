<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick-framework)
 *
 * @link       https://github.com/milejko/kuick-framework
 * @copyright  Copyright (c) 2010-2024 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\App;

use DI\ContainerBuilder;
use Kuick\App\Services\BuildActionMatcher;
use Kuick\App\Services\BuildCommandMatcher;
use Kuick\App\Services\BuildConfiguration;
use Kuick\App\Services\BuildLogger;
use Monolog\Level;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

/**
 *
 */
class AppDIContainerBuilder
{
    private const CACHE_PATH = BASE_PATH . '/var/tmp';
    private const COMPILED_FILENAME = 'CompiledContainer.php';
    private const APP_ENV_KEY = 'kuick.app.env';
    private const READY_DEFINITION = 'kuick.app.name';

    private array $envVars = [];
    private string $appEnv;

    public function __invoke(): ContainerInterface
    {
        //loading environment variables
        $this->envVars = (new AppGetEnvironment())();
        //determining kuick.app.env (ie. dev, prod)
        $this->appEnv = $this->envVars[self::APP_ENV_KEY] ?? Application::ENV_PROD;

        //remove previous compilation if KUICK_APP_ENV!=dev
        if ($this->appEnv == Application::ENV_DEV) {
            $this->removeContainer();
        }

        //build or load from cache
        $container = $this->configureBuilder()->build();

        //validating if container is built
        if ($container->has(self::READY_DEFINITION)) {
            $logger = $container->get(LoggerInterface::class);
            $logger->info('Application is running in ' . $this->appEnv . ' mode');
            $logger->info('DI container loaded from cache');
            return $container;
        }

        //rebuilding if validation failed
        $container = $this->rebuildContainer();
        $logger = $container->get(LoggerInterface::class);
        $logger->log(
            $this->appEnv == Application::ENV_DEV ? Level::Warning : Level::Info,
            'Application is running in ' . $this->appEnv . ' mode'
        );
        $logger->notice('DI container rebuilt');
        return $container;
    }

    private function rebuildContainer(): ContainerInterface
    {
        $this->removeContainer();
        $builder = $this->configureBuilder();

        //DI definitions (configuration)
        (new BuildConfiguration($builder))($this->appEnv);

        //load environment variables
        $builder->addDefinitions($this->envVars);
        
        //logger
        (new BuildLogger($builder))();

        //action matcher
        (new BuildActionMatcher($builder))();

        //command matcher
        (new BuildCommandMatcher($builder))();

        return $builder->build();
    }

    private function configureBuilder(): ContainerBuilder
    {
        $builder = (new ContainerBuilder())
            ->useAutowiring(true)
            ->useAttributes(true)
            ->enableCompilation(self::CACHE_PATH . DIRECTORY_SEPARATOR . $this->appEnv);
        if ($this->isApcuEnabled()) {
            $builder->enableDefinitionCache(__DIR__);
        }
        return $builder;
    }

    private function removeContainer(): void
    {
        /** @disregard P1009 Undefined type */
        $this->isApcuEnabled() && apcu_clear_cache();
        array_map('unlink', glob(self::CACHE_PATH . DIRECTORY_SEPARATOR . $this->appEnv . DIRECTORY_SEPARATOR . self::COMPILED_FILENAME));
    }

    private function isApcuEnabled(): bool
    {
        /** @disregard P1009 Undefined type */
        return function_exists('apcu_enabled') && apcu_enabled();
    }
}
