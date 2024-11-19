<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick-framework)
 *
 * @link       https://github.com/milejko/kuick-framework
 * @copyright  Copyright (c) 2010-2024 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\UI;

use Kuick\Console\CommandMatcher;

class CommandListingCommand implements CommandInterface
{
    private const COMMAND_HEADER = "=================================================================================\nCommand name                     Description\n=================================================================================\n";
    private const COMMAND_LINE_TEMPLATE = "%s %s\n";
    private const COMMAND_FOOTER = '---------------------------------------------------------------------------------';

    public function __construct(private CommandMatcher $commandMatcher)
    {
    }

    public function __invoke(array $arguments): string
    {
        $commandList = '';
        foreach ($this->commandMatcher->getCommands() as $command) {
            $commandList .= sprintf(self::COMMAND_LINE_TEMPLATE, str_pad($command['name'], 32), isset($command['description']) ? substr($command['description'], 0, 48) : '-');
        }
        return self::COMMAND_HEADER . $commandList . self::COMMAND_FOOTER;
    }
}
