<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick-framework)
 *
 * @link       https://github.com/milejko/kuick-framework
 * @copyright  Copyright (c) 2010-2024 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\Router;

use Kuick\App\AppException;
use Kuick\App\RoutesConfig;
use Kuick\Console\ConsoleIndexCommand;

/**
 *
 */
class CommandMatcher
{
    private const LIST_COMMAND = ['name' => 'default', 'command' => ConsoleIndexCommand::class];

    public function __construct(private RoutesConfig $routes)
    {
    }

    public function getCommands(): array
    {
        return $this->routes->getAll();
    }

    public function findRoute(array $arguments): array
    {
        if (!isset($arguments[1])) {
            return self::LIST_COMMAND;
        }
        foreach ($this->routes->getAll() as $commandName => $command) {
            (new CommandRouteValidator)($commandName, $command);
            if ($arguments[1] == $commandName) {
                return $command;
            }
        }
        throw new AppException('Command not found');
    }
}
