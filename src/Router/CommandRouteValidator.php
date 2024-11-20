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

/**
 *
 */
class CommandRouteValidator
{
    private const COMMAND_PARAM_COUNT = 2;
    private const MIN_COMMAND_NAME_LENGTH = 3;
    private const MAX_COMMAND_NAME_LENGTH = 40;

    public function __invoke(string $commandName, array $command): void
    {
        $paramCount = self::COMMAND_PARAM_COUNT;
        if (!isset($command['description'])) {
            $paramCount--;
        }
        if (strlen($commandName) > self::MAX_COMMAND_NAME_LENGTH ||
            strlen($commandName) < self::MIN_COMMAND_NAME_LENGTH
        ) {
            throw new ConsoleException('Command name ' . $commandName . ' is to short(' . self::MIN_COMMAND_NAME_LENGTH . ') or too long(' . self::MAX_COMMAND_NAME_LENGTH . ')');
        }
        if (count($command) != $paramCount) {
            throw new ConsoleException('Command: ' . $commandName . ' has invalid parameter count');
        }
        if (!isset($command['command'])) {
            throw new ConsoleException('Command: ' . $commandName . ' is missing action class name');
        }
        if (!class_exists($command['command'])) {
            throw new ConsoleException('Command: ' . $commandName . ' class "' . $command['command'] . '" does not exist');
        }
    }
}
