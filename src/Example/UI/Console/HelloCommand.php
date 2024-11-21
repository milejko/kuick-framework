<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick-framework)
 *
 * @link       https://github.com/milejko/kuick-framework
 * @copyright  Copyright (c) 2010-2024 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\Example\UI\Console;

use Kuick\UI\CommandInterface;

class HelloCommand implements CommandInterface
{
    private const MESSAGE_TEMPLATE = 'Kuick says: Hello %s!';
    private const DEFAULT_NAME = 'you';

    public function __invoke(array $arguments): string
    {
        $name = $arguments[0] ?? self::DEFAULT_NAME;
        return sprintf(self::MESSAGE_TEMPLATE, $name);
    }
}
