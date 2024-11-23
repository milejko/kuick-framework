<?php

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