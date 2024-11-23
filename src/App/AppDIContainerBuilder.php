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
use Kuick\Utils\DotEnvParser;
use Monolog\Handler\BrowserConsoleHandler;
use Monolog\Handler\FirePHPHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

/**
 *
 */
class AppDIContainerBuilder
{
    private const DEFAULT_CONFIG_SETTINGS = [
        'kuick.app.charset'   => 'UTF-8',
        'kuick.app.locale'    => 'en_US.utf-8',
        'kuick.app.timezone'  => 'UTC',
        'kuick.ops.guards.token' => 'please-change-this-token',
        'kuick.monolog.level' => 'DEBUG',
        'kuick.monolog.handlers' => [],
    ];
    private const CONTAINER_DEFINITION_LOCATIONS = [
        BASE_PATH . '/etc/di/*.di.php',
        BASE_PATH . '/vendor/kuick/*/etc/di/*.di.php',
    ];
    private const ENV_SPECIFIC_LOCATION_TEMPLATE = BASE_PATH . '/etc/di/*.di@%s.php';

    private const CONTAINER_PATH = BASE_PATH . '/var/tmp';
    private const CONTAINER_FILENAME = 'CompiledContainer.php';
    private const CONTAINER_READY_FLAG = 'kuick.app.container_ready';

    private string $env;

    public function __invoke(): ContainerInterface
    {
        $this->env = $this->determineEnv();
        //remove previous compilation if KUICK_APP_ENV!=dev
        if ($this->env == Application::ENV_DEV) {
            $this->removeContainer();
        }
        //build or load from cache
        $container = $this->getBuilder()->build();
        //validating if container is built
        if ($container->has(self::CONTAINER_READY_FLAG)) {
            return $container;
        }
        //rebuilding if validation failed
        return $this->rebuildContainer();
    }

    private function rebuildContainer(): ContainerInterface
    {
        $this->removeContainer();
        $builder = $this->getBuilder();

        //mandatory defaults
        $builder->addDefinitions(self::DEFAULT_CONFIG_SETTINGS);

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

        //adding environment definitions
        $this->addEnvironmentDefinitions($builder);

        $builder->addDefinitions([LoggerInterface::class => function (ContainerInterface $container): LoggerInterface {
            $logger = new Logger($container->get('kuick.app.name'));
            $handlers = $container->get('kuick.monolog.handlers');
            !is_array($handlers) && throw new AppException('Logger handlers are invalid, should be an array');
            foreach ($handlers as $handler) {
                $type = $handler['type'] ?? throw new AppException('Logger handler type not defined');
                $level = $handler['level'] ?? 'WARNING';
                //@TODO: handle more types
                if ('firePHP' == $type) {
                    $logger->pushHandler(new FirePHPHandler($level));
                }
                if ('stream' == $type) {
                    $logger->pushHandler(new StreamHandler($handler['path'] ?? throw new AppException('Logger handler type not defined'), $level));
                }
                if ('console' == $type) {
                    $logger->pushHandler(new BrowserConsoleHandler($level));
                }
            }
            return $logger;
        }]);
        $builder->addDefinitions([ActionMatcher::class => function (ContainerInterface $container): ActionMatcher {
            $routes = [];
            //app config (normal priority)
            foreach (glob(BASE_PATH . '/etc/routes/*.actions.php') as $routeFile) {
                $routes = array_merge($routes, include $routeFile);
            }
            //validating routes
            foreach ($routes as $route) {
                (new ActionValidator())($route);
            }
            $actionMatcher = (new ActionMatcher($container->get(LoggerInterface::class)))->setRoutes(new RoutesConfig($routes));
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

    private function addEnvironmentDefinitions(ContainerBuilder $builder): void
    {
        //dot env files .env and .env.$env (lower priority)
        $dotEnvFile = BASE_PATH . '/.env';
        $dotEnvLocalFile = BASE_PATH . '/.env.local';
        $dotEnvValues = array_merge(
            file_exists($dotEnvFile) ? (new DotEnvParser)($dotEnvFile) : [],
            file_exists($dotEnvLocalFile) ? (new DotEnvParser)($dotEnvLocalFile) : []
        );
        $builder->addDefinitions($dotEnvValues);
        //environment variables (higher)
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
            ->enableCompilation(self::CONTAINER_PATH . DIRECTORY_SEPARATOR . $this->env);
        if ($this->isApcuEnabled()) {
            $builder->enableDefinitionCache(__DIR__);
        }
        return $builder;
    }

    private function removeContainer(): void
    {
        /** @disregard P1009 Undefined type */
        $this->isApcuEnabled() && apcu_clear_cache();
        array_map('unlink', glob(self::CONTAINER_PATH . DIRECTORY_SEPARATOR . $this->env . DIRECTORY_SEPARATOR . self::CONTAINER_FILENAME));
    }

    private function isApcuEnabled(): bool
    {
        return function_exists('apcu_clear_cache') && ini_get('apc.enabled')
            && !('cli' === \PHP_SAPI && !ini_get('apc.enable_cli'));
    }

    private function determineEnv(): string
    {
        $environmentVariable = getenv(Application::APP_ENV);
        if ($environmentVariable) {
            return $environmentVariable;
        }
        $dotEnvFile = BASE_PATH . '/.env';
        $dotEnvLocalFile = BASE_PATH . '/.env.local';
        $dotEnvValues = array_merge(
            file_exists($dotEnvFile) ? (new DotEnvParser)($dotEnvFile) : [],
            file_exists($dotEnvLocalFile) ? (new DotEnvParser)($dotEnvLocalFile) : []
        );
        if (isset($dotEnvValues['kuick.app.env'])) {
            return $dotEnvValues['kuick.app.env'];
        }
        return Application::ENV_PROD;
    }
}
