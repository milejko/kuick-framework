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
use Kuick\Console\ConsoleIndexCommand;
use Psr\Log\LoggerInterface;

/**
 *
 */
class CommandMatcher
{
    private const LIST_COMMAND = ['command' => ConsoleIndexCommand::class];

    private array $routes;

    public function __construct(private LoggerInterface $logger)
    {
    }

    public function setRoutes(array $routes): self
    {
        $this->routes = $routes;
        return $this;
    }

    public function getRoutes(): array
    {
        return $this->routes;
    }

    public function findRoute(array $arguments): array
    {
        if (!isset($arguments[1])) {
            return self::LIST_COMMAND;
        }
        foreach ($this->routes as $command) {
            if ($arguments[1] == $command['name']) {
                $this->logger->info('Command matched: ' . $command['name']);
                return $command;
            }
        }
        throw new ConsoleException('Command not found');
    }
}
