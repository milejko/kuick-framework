<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick-framework)
 *
 * @link       https://github.com/milejko/kuick-framework
 * @copyright  Copyright (c) 2010-2024 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

return [
    //no token for dev
    'kuick.app.ops.guards.token' => '',

    //debug for dev
    'kuick.app.monolog.level' => 'DEBUG',
    //different handlers for dev
    'kuick.app.monolog.handlers' => [
        [
            'type' => 'stream',
            'path' => 'php://stdout',
        ],
        [
            'type' => 'console',
        ],
    ],
];