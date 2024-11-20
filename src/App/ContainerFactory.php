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
    private const CONTAINER_PATH = BASE_PATH . '/var/tmp';
    private const CONTAINER_FILENAME = 'CompiledContainer.php';

    public static function create(array $definitionPathPatterns): ContainerInterface
    {
        //remove previous compilation if cache disabled
        if (getenv('APP_ENV') == 'dev') {
            self::removeContainer();
        }
        $builder = self::getBuilder();
        $container = $builder->build();
        if ($container->has('container.built')) {
            return $container;
        }
        //clear build
        self::removeContainer();
        $builder = self::getBuilder();
        //adding definitions
        foreach ($definitionPathPatterns as $definitionsLocation) {
            foreach (glob($definitionsLocation) as $definitionFile) {
                $builder->addDefinitions($definitionFile);
            }
        }
        $builder->addDefinitions(['container.built' => true]);
        return $builder->build();
    }

    private static function getBuilder(): ContainerBuilder
    {
        return (new ContainerBuilder())
            ->useAutowiring(true)
            ->useAttributes(true)
            ->enableCompilation(self::CONTAINER_PATH);
    }

    private static function removeContainer(): void
    {
        array_map('unlink', glob(self::CONTAINER_PATH . DIRECTORY_SEPARATOR . self::CONTAINER_FILENAME));
    }
}