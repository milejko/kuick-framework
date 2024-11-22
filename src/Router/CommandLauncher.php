<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick-framework)
 *
 * @link       https://github.com/milejko/kuick-framework
 * @copyright  Copyright (c) 2010-2024 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\Router;

use Kuick\Console\ConsoleException;
use Kuick\UI\CommandInterface;
use Psr\Container\ContainerInterface;
use Throwable;

/**
 *
 */
class CommandLauncher
{
    public function __construct(private ContainerInterface $container) {}

    public function __invoke(array $command, array $arguments): string
    {
        try {
            $command = $this->container->get($command['command']);
            if (!($command instanceof CommandInterface)) {
                throw new ConsoleException($command['command'] . ' is not a Command');
            }
            return $command->__invoke(array_slice($arguments, 2));
        } catch (Throwable $error) {
            echo $error->getMessage() . PHP_EOL;
            exit(1);
        }
    }
}
