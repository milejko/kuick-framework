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
use Psr\Container\ContainerInterface;

/**
 *
 */
class ContainerFactory
{
    private const CONTAINER_DEFINITION_LOCATIONS = [
        BASE_PATH . '/etc/di/*.di.php',
        BASE_PATH . '/vendor/kuick/*/etc/di/*.di.php',
    ];
    private const ENV_SPECIFIC_LOCATION_TEMPLATE = BASE_PATH . '/etc/di/*.di@%s.php';

    private const CONTAINER_PATH = BASE_PATH . '/var/tmp';
    private const CONTAINER_FILENAME = 'CompiledContainer.php';

    public static function create(): ContainerInterface
    {
        //remove previous compilation if APP_ENV!=dev
        if (Application::getAppEnv() == Application::APP_ENV_DEV) {
            self::removeContainer();
        }
        $builder = self::getBuilder();
        $container = $builder->build();
        //container is containg Configuration, therfore it is built
        if ($container->has('container.built')) {
            return $container;
        }
        //clear build
        self::removeContainer();
        $builder = self::getBuilder();
        //adding global definitions
        foreach (self::CONTAINER_DEFINITION_LOCATIONS as $definitionsLocation) {
            foreach (glob($definitionsLocation) as $definitionFile) {
                $builder->addDefinitions($definitionFile);
            }
        }
        //adding env specific definitions
        foreach (glob(sprintf(self::ENV_SPECIFIC_LOCATION_TEMPLATE, Application::getAppEnv())) as $definitionFile) {
            $builder->addDefinitions($definitionFile);
        }
        $builder->addDefinitions(['container.built' => true]);
        return $builder->build();
    }

    private static function getBuilder(): ContainerBuilder
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

    private static function removeContainer(): void
    {
        self::isApcuEnabled() && apcu_clear_cache();
        array_map('unlink', glob(self::CONTAINER_PATH . DIRECTORY_SEPARATOR . self::CONTAINER_FILENAME));
    }

    private static function isApcuEnabled(): bool
    {
        return function_exists('apcu_clear_cache') && ini_get('apc.enabled')
            && !('cli' === \PHP_SAPI && !ini_get('apc.enable_cli'));
    }
}
