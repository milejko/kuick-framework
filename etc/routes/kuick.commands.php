<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick-framework)
 *
 * @link       https://github.com/milejko/kuick-framework
 * @copyright  Copyright (c) 2010-2024 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

use Kuick\UI\Example\Console\HelloCommand;

return [
    //You probably need to remove this sample command
    'hello' => [
        'command' => HelloCommand::class,
        //'description' => 'Optional description'
    ]
];