<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick-framework)
 *
 * @link       https://github.com/milejko/kuick-framework
 * @copyright  Copyright (c) 2010-2024 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\Console;

/**
 *
 */
class CommandValidator
{
    private const COMMAND_PARAM_COUNT = 3;

    public function __invoke(array $command): void
    {
        if (count($command) != self::COMMAND_PARAM_COUNT) {
            throw new ConsoleException('Command has invalid parameter count');
        }
        if (!isset($command['description'])) {
            throw new ConsoleException('Command description is missing');
        }
        if (!isset($command['name'])) {
            throw new ConsoleException('Command name is missing');
        }
        if (!isset($command['command'])) {
            throw new ConsoleException('Command is missing action class name');
        }
        if (!class_exists($command['command'])) {
            throw new ConsoleException('Command "' . $command['command'] . '" does not exist');
        }
    }
}
