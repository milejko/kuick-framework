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
use Psr\Container\ContainerInterface;

return [
    'kuick.app.name'      => 'Kuick app',
    'kuick.app.charset'   => 'UTF-8',
    'kuick.app.locale'    => 'en_US.utf-8',
    'kuick.app.timezone'  => 'UTC',

    'kuick.monolog.level' => 'WARNING',
    'kuick.monolog.handlers' => [
        [
            'type' => 'stream',
            'path' => 'php://stdout',
        ],
    ],

    'kuick.ops.guards.token' => 'secret-token',
];