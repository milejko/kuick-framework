<?php

use Psr\Container\ContainerInterface;

/**
 * PHP-DI definitions
 * @see https://php-di.org/doc/php-definitions.html
 */
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
 
    //autowiring
    //SomeInterface::class => DI\autowire(SomeImplementation::class),

    //create
    //LoggerInterface::class => DI\create(Logger::class),
 
    //factory
    //AnotherInterface::class => function (ContainerInterface $container) {
    //    return new AnotherClass($container->get('something'));
    //},
];