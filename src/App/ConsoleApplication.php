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
 * Console application
 */
class ConsoleApplication
{
    private const CONTAINER_DEFINITION_LOCATIONS = [
        BASE_PATH . '/etc/di/*.di.php',
        BASE_PATH . '/vendor/kuick-framework/etc/di/*.di.php',
    ];
    private const CONTAINER_ENV_SPECIFIC_DEFINITION_LOCATIONS = [
        BASE_PATH . '/etc/di/*.di.%s.php',
        BASE_PATH . '/vendor/kuick-framework/etc/di/*.di.%s.php',
    ];

    private const NEW_LINE_CHAR = "\n";

    public function __invoke(array $arguments): void
    {
        try {
            $container = ContainerFactory::create(
                self::CONTAINER_DEFINITION_LOCATIONS,
                self::CONTAINER_ENV_SPECIFIC_DEFINITION_LOCATIONS
            );

            ini_set('max_execution_time', 0);
            ini_set('memory_limit', '2048M');
            //@TODO: Command input/output instead of strings
            echo $container->get(CommandLauncher::class)(
                $container->get(CommandMatcher::class)->findRoute($arguments),
                $arguments
            ) . self::NEW_LINE_CHAR;
            exit(0);
        } catch (Throwable $error) {
            echo $error->getMessage() . self::NEW_LINE_CHAR;
            exit($error->getCode());
        }
    }
}