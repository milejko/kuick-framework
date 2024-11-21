<?php

/**
 * PHP-DI definitions
 * @see https://php-di.org/doc/php-definitions.html
 */

use Psr\Container\ContainerInterface;

return [
    //values (aka parameters)
    'some.value' => 'value',
 
    //autowiring
    //SomeInterface::class => DI\autowire(SomeImplementation::class),

    //create
    //LoggerInterface::class => DI\create(Logger::class),
 
    //factory
    //AnotherInterface::class => function (ContainerInterface $container) {
    //    return new AnotherClass($container->get('something'));
    //},
];