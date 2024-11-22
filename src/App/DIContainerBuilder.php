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
use Kuick\Router\ActionMatcher;
use Kuick\Router\ActionValidator;
use Kuick\Router\CommandMatcher;
use Kuick\Router\CommandRouteValidator;
use Psr\Container\ContainerInterface;

/**
 *
 */
class DIContainerBuilder
{
    private const CONTAINER_DEFINITION_LOCATIONS = [
        BASE_PATH . '/etc/di/*.di.php',
        BASE_PATH . '/vendor/kuick/*/etc/di/*.di.php',
    ];
    private const ENV_SPECIFIC_LOCATION_TEMPLATE = BASE_PATH . '/etc/di/*.di@%s.php';

    private const CONTAINER_PATH = BASE_PATH . '/var/tmp';
    private const CONTAINER_FILENAME = 'CompiledContainer.php';
    private const CONTAINER_READY_FLAG = 'kuick.app.container_ready';

    private string $env;

    public function __invoke(string $env): ContainerInterface
    {
        $this->env = $env;
        //remove previous compilation if APP_ENV!=dev
        if ($env == Application::ENV_DEV) {
            self::removeContainer();
        }
        //build or load from cache
        $container = self::getBuilder()->build();
        //validating if container is built
        if ($container->has(self::CONTAINER_READY_FLAG)) {
            return $container;
        }
        //rebuilding if validation failed
        return self::rebuildContainer();
    }

    private function rebuildContainer(): ContainerInterface
    {
        self::removeContainer();
        $builder = self::getBuilder();

        //adding configuration definitions
        self::addConfigDefinitions($builder);

        //adding global definitions
        foreach (self::CONTAINER_DEFINITION_LOCATIONS as $definitionsLocation) {
            foreach (glob($definitionsLocation) as $definitionFile) {
                $builder->addDefinitions($definitionFile);
            }
        }
        //adding env specific definitions
        foreach (glob(sprintf(self::ENV_SPECIFIC_LOCATION_TEMPLATE, $this->env)) as $definitionFile) {
            $builder->addDefinitions($definitionFile);
        }
        $builder->addDefinitions([ActionMatcher::class => function () {
            $routes = [];
            //app config (normal priority)
            foreach (glob(BASE_PATH . '/etc/routes/*.actions.php') as $routeFile) {
                $routes = array_merge($routes, include $routeFile);
            }
            //validating routes
            foreach ($routes as $route) {
                (new ActionValidator())($route);
            }
            $actionMatcher = new ActionMatcher(new RoutesConfig($routes));
            return $actionMatcher;
        }]);
        $builder->addDefinitions([CommandMatcher::class => function () {
            $commands = [];
            //app commands (normal priority)
            foreach (glob(BASE_PATH . '/etc/routes/*.commands.php') as $commandFile) {
                $commands = array_merge($commands, include $commandFile);
            }
            foreach ($commands as $command) {
                (new CommandRouteValidator())($command);
            }
            $commandMatcher = new CommandMatcher(new RoutesConfig($commands));
            return $commandMatcher;
        }]);
        $builder->addDefinitions([self::CONTAINER_READY_FLAG => true]);
        return $builder->build();        
    }

    private function addConfigDefinitions(ContainerBuilder $builder): void
    {
        //vendor config (lower priority priority)
        foreach (glob(BASE_PATH . '/vendor/kuick/*/etc/*.config.php') as $configFile) {
            $builder->addDefinitions($configFile);
        }
        //app config (normal priority)
        foreach (glob(BASE_PATH . '/etc/*.config.php') as $configFile) {
            $builder->addDefinitions(include$configFile);
        }
        //environment specific config (higher priority)
        foreach (glob(BASE_PATH . '/etc/*.config@' . $this->env . '.php') as $configFile) {   
            $builder->addDefinitions(include $configFile);
        }
        //environment variables (the highest priority)
        foreach (getenv() as $envVarKey => $envVarValue) {
            $sanitizedKey = str_replace('_', '.', strtolower($envVarKey));
            $builder->addDefinitions([$sanitizedKey => $envVarValue]);
        }
    }

    private function getBuilder(): ContainerBuilder
    {
        $builder = (new ContainerBuilder())
            ->useAutowiring(true)
            ->useAttributes(true)
            ->enableCompilation(self::CONTAINER_PATH);
        if (self::isApcuEnabled()) {
            $builder->enableDefinitionCache(__DIR__);
        }
        return $builder;
    }

    private function removeContainer(): void
    {
        self::isApcuEnabled() && apcu_clear_cache();
        array_map('unlink', glob(self::CONTAINER_PATH . DIRECTORY_SEPARATOR . self::CONTAINER_FILENAME));
    }

    private function isApcuEnabled(): bool
    {
        return function_exists('apcu_clear_cache') && ini_get('apc.enabled')
            && !('cli' === \PHP_SAPI && !ini_get('apc.enable_cli'));
    }
}
