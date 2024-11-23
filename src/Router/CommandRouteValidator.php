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
    private const MAX_ROUTE_PARAMS = 3;
    private const MIN_COMMAND_NAME_LENGTH = 3;
    private const MAX_COMMAND_NAME_LENGTH = 40;

    public function __invoke(array $command): void
    {
        $parameterCount = self::MAX_ROUTE_PARAMS;
        if (!isset($command['description'])) {
            $parameterCount--;
        }
        $this->validateName($command);
        if (count($command) != $parameterCount) {
            throw new ConsoleException('Command: ' . $command['name'] . ' has invalid parameter count');
        }
        if (!isset($command['command'])) {
            throw new ConsoleException('Command: ' . $command['name'] . ' is missing action class name');
        }
        if (!class_exists($command['command'])) {
            throw new ConsoleException('Command: ' . $command['name'] . ' class "' . $command['command'] . '" does not exist');
        }
    }

    private function validateName(array $command): void
    {
        if (!isset($command['name'])) {
            throw new ConsoleException('One or more commands are missing name');
        }
        if (!is_string($command['name'])) {
            throw new ConsoleException('One or more commands name is invalid');
        }
        if (strlen($command['name']) > self::MAX_COMMAND_NAME_LENGTH ||
            strlen($command['name']) < self::MIN_COMMAND_NAME_LENGTH
        ) {
            throw new ConsoleException('Command name ' . $command['name'] . ' is to short(' . self::MIN_COMMAND_NAME_LENGTH . ') or too long(' . self::MAX_COMMAND_NAME_LENGTH . ')');
        }
    }
}
