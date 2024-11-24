<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick-framework)
 *
 * @link       https://github.com/milejko/kuick-framework
 * @copyright  Copyright (c) 2010-2024 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\Console;

use Kuick\Router\CommandMatcher;
use Kuick\UI\CommandInterface;

class ConsoleIndexCommand implements CommandInterface
{
    private const COMMAND_HEADER = "=================================================================================\nCommand name                     Usage\n=================================================================================\n";
    private const COMMAND_LINE_TEMPLATE = "%s %s\n";
    private const COMMAND_FOOTER = '---------------------------------------------------------------------------------';
    private const USAGE_TEMPLATE = './bin/console %s (arg1 arg2 ...)';

    public function __construct(private CommandMatcher $commandMatcher)
    {
    }

    public function __invoke(array $arguments): string
    {
        $commandList = '';
        foreach ($this->commandMatcher->getRoutes() as $route) {
            $commandList .= sprintf(self::COMMAND_LINE_TEMPLATE, str_pad($route['name'], 32), $route['description'] ?? sprintf(self::USAGE_TEMPLATE, $route['name']));
        }
        return self::COMMAND_HEADER . $commandList . self::COMMAND_FOOTER;
    }
}
