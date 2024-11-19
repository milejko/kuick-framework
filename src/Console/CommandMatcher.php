<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick-framework)
 *
 * @link       https://github.com/milejko/kuick-framework
 * @copyright  Copyright (c) 2010-2024 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\Console;

use Kuick\App\KernelException;
use Kuick\UI\CommandListingCommand;

/**
 *
 */
class CommandMatcher
{
    private const LIST_COMMAND = ['name' => 'default', 'command' => CommandListingCommand::class];

    public function __construct(private array $commands = [])
    {
    }

    public function getCommands(): array
    {
        return $this->commands;
    }

    public function matchCommand(array $arguments): array
    {
        if (!isset($arguments[1])) {
            return self::LIST_COMMAND;
        }
        foreach ($this->commands as $command) {
            (new CommandValidator)($command);
            if ($arguments[1] == $command['name']) {
                return $command;
            }
        }
        throw new KernelException('Command not found');
    }
}