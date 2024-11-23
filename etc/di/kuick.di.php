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

    'kuick.ops.guards.token' => 'secret-token',

    'kuick.monolog.handlers' => [
        [
            'type' => 'stream',
            'path' => 'php://stdout',
        ]
    ],
 
    //autowiring
    //SomeInterface::class => DI\autowire(SomeImplementation::class),

    //create
    //LoggerInterface::class => DI\create(Logger::class),
 
    //factory
    //AnotherInterface::class => function (ContainerInterface $container) {
    //    return new AnotherClass($container->get('something'));
    //},
];