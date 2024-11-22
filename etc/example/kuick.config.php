<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick-framework)
 *
 * @link       https://github.com/milejko/kuick-framework
 * @copyright  Copyright (c) 2010-2024 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

return [
    'kuick.app.charset'   => 'UTF-8',
    'kuick.app.locale'    => 'en_US.utf-8',
    'kuick.app.timezone'  => 'UTC',

    'kuick.monolog.level' => 'DEBUG',
    //additional handlers
    'kuick.monolog.handlers' => [
        [
            'type' => 'stream',
            'path' => '/var/log/path-to-the-custom-log-file.log',
            'level' => 'DEBUG',
        ]
    ],

    'kuick.ops.guards.token' => 'secret-token',
];