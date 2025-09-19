<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick-framework)
 *
 * @link       https://github.com/milejko/kuick-framework
 * @copyright  Copyright (c) 2010-2025 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://github.com/milejko/kuick-framework?tab=MIT-1-ov-file#readme New BSD License
 */

namespace Kuick\Framework\Config;

use Psr\Http\Server\MiddlewareInterface;

/**
 * Middleware config validator
 */
final class MiddlewareConfigValidator
{
    public function validate(MiddlewareConfig $configObject): void
    {
        //callable empty
        if (empty($configObject->middlewareClassName)) {
            throw new ConfigException("Middleware class name should not be empty");
        }
        //inexistent class
        if (!class_exists($configObject->middlewareClassName)) {
            throw new ConfigException("Middleware class: $configObject->middlewareClassName does not exist");
        }
        //not a subclass of middleware
        if (!is_subclass_of($configObject->middlewareClassName, MiddlewareInterface::class)) {
            throw new ConfigException("Middleware does not implement MiddlewareInterface: $configObject->middlewareClassName");
        }
        if (null === $configObject->beforeMiddlewareClassName) {
            return;
        }
        //inexistent before class
        if (!class_exists($configObject->beforeMiddlewareClassName)) {
            throw new ConfigException("Before middleware class: $configObject->beforeMiddlewareClassName does not exist");
        }
        //not a subclass of middleware
        if (!is_subclass_of($configObject->beforeMiddlewareClassName, MiddlewareInterface::class)) {
            throw new ConfigException("Before middleware does not implement MiddlewareInterface: $configObject->beforeMiddlewareClassName");
        }
    }
}
