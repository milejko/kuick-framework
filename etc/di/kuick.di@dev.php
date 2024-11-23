<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick-framework)
 *
 * @link       https://github.com/milejko/kuick-framework
 * @copyright  Copyright (c) 2010-2024 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

/**
 * PHP-DI definitions
 * @see https://php-di.org/doc/php-definitions.html
 */
return [
    //no token for dev
    'kuick.ops.guards.token' => '',
    //debug for dev
    'kuick.monolog.level' => 'DEBUG',
    
    //different handlers
    'kuick.monolog.handlers' => [
        [
            'type' => 'stream',
            'path' => 'php://stdout',
            'level' => 'DEBUG',
        ],
        [
            'type' => 'console',
            'level' => 'DEBUG',
        ]
    ],
];