<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick-framework)
 *
 * @link       https://github.com/milejko/kuick-framework
 * @copyright  Copyright (c) 2010-2024 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\Router;

use Kuick\App\KernelException;

/**
 *
 */
class CommandValidator
{
    private const MAX_COMMAND_PARAMS = 2;

    public function __invoke(array $command): void
    {
        if (count($command) != self::MAX_COMMAND_PARAMS) {
            throw new KernelException('Command has invalid parameter count');
        }
        if (!isset($command['name'])) {
            throw new KernelException('Command is missing name');
        }
        $this->validateAction($command);
    }

    private function validateAction(array $route): void
    {
        if (!isset($route['action'])) {
            throw new KernelException('Command is missing action class name');
        }
        if (!class_exists($route['action'])) {
            throw new KernelException('Action "' . $route['action'] . '" does not exist');
        }
    }
}
