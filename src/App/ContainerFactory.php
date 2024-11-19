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
use Kuick\UI\ActionInterface;
use Kuick\UI\GuardInterface;
use Psr\Container\ContainerInterface;
use ReflectionClass;

use function DI\autowire;

/**
 *
 */
class ContainerFactory
{
    private const CONTAINER_PATH = BASE_PATH . '/var';
    private const CONTAINER_FILENAME = 'CompiledContainer.php';

    public static function create(array $definitionPathPatterns, array $classesPathPatterns): ContainerInterface
    {
        //remove previous compilation if cache disabled
        getenv('APP_DEVELOPMENT') && self::unlinkCompiledContainer();
        $builder = self::getBuilder();
        $container = $builder->build();
        if ($container->has('container.built')) {
            return $container;
        }
        //clear build
        self::unlinkCompiledContainer();
        $builder = self::getBuilder();
        //adding definitions
        foreach ($definitionPathPatterns as $definitionsLocation) {
            foreach (glob($definitionsLocation) as $definitionFile) {
                $builder->addDefinitions($definitionFile);
            }
        }
        //adding class definitions
        $declaredClasses = get_declared_classes();
        foreach ($classesPathPatterns as $classLocation) {
            foreach (glob($classLocation) as $classFile) {
                include_once $classFile;
                $freshlyDeclaredClasses = get_declared_classes();
                foreach (array_diff($freshlyDeclaredClasses, $declaredClasses) as $className) {
                    $relectionClass = new ReflectionClass($className);
                    if ($relectionClass->isInterface() || $relectionClass->isAbstract()) {
                        continue;
                    }
                    if ($relectionClass->implementsInterface(ActionInterface::class) || $relectionClass->implementsInterface(GuardInterface::class)) {
                        $builder->addDefinitions([$className => autowire($className)]);
                    }
                }
                $declaredClasses = $freshlyDeclaredClasses;
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

    private static function unlinkCompiledContainer(): void
    {
        array_map('unlink', glob(self::CONTAINER_PATH . DIRECTORY_SEPARATOR . self::CONTAINER_FILENAME));
    }
}