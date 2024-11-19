<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick-framework)
 *
 * @link       https://github.com/milejko/kuick-framework
 * @copyright  Copyright (c) 2010-2024 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\App;

use Kuick\Router\CommandLauncher;
use Kuick\Router\CommandMatcher;
use Throwable;

/**
 *
 */
class CLIKernel
{
    private const CONTAINER_DEFINITION_LOCATIONS = [
        BASE_PATH . '/etc/*.di.php',
        BASE_PATH . '/vendor/kuick-framework/etc/*.di.php',
    ];
    private const CONTAINER_CLASS_LOCATIONS = [
        BASE_PATH . '/src/UI/*.php',
        BASE_PATH . '/vendor/kuick-framework/src/UI/*.php',
    ];

    public function __invoke(array $arguments): void
    {
        try {
            $container = ContainerFactory::create(self::CONTAINER_DEFINITION_LOCATIONS, self::CONTAINER_CLASS_LOCATIONS);
            echo $container->get(CommandLauncher::class)(
                $container->get(CommandMatcher::class)->matchCommand($arguments),
                $arguments
            ) . "\n";
        } catch (Throwable $error) {
            echo $error->getMessage();
        }
    }

}