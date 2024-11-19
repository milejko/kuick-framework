<?php

/**
 * Message Broker
 *
 * @link       https://github.com/milejko/message-broker.git
 * @copyright  Copyright (c) 2024 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\Router;

use Kuick\Http\HttpException;
use Kuick\Http\HttpMethod;

/**
 *
 */
class RouteValidator
{
    private const MAX_ROUTE_PARAMS = 4;

    public function __invoke(array $route): void
    {
        $this->validateParameterCount($route);
        $this->validateMethod($route);
        $this->validatePath($route);
        $this->validateAction($route);
        $this->validateGuards($route);
    }

    private function validateParameterCount(array $route): void
    {
        $parameterCount = self::MAX_ROUTE_PARAMS;
        if (!isset($route['method'])) {
            $parameterCount--;
        }
        if (!isset($route['guards'])) {
            $parameterCount--;
        }
        if (count($route) != $parameterCount) {
            throw new HttpException('Route has invalid parameter count');
        }
    }

    private function validateMethod(array $route): void
    {
        if (isset($route['method']) && !in_array($route['method'], HttpMethod::ALL_METHODS)) {
            throw new HttpException('Route method invalid');
        }
    }

    private function validatePath(array $route): void
    {
        if (!isset($route['path'])) {
            throw new HttpException('Route is missing path');
        }
        if (!is_string($route['path'])) {
            throw new HttpException('Route path must be a string');
        }
    }

    private function validateAction(array $route): void
    {
        if (!isset($route['action'])) {
            throw new HttpException('Route is missing action class name');
        }
        if (!class_exists($route['action'])) {
            throw new HttpException('Action "' . $route['action'] . '" does not exist');
        }
    }

    private function validateGuards(array $route): void
    {
        if (!isset($route['guards'])) {
            return;
        }
        if (!is_array($route['guards'])) {
            throw new HttpException('Route guards malformed, not an array');
        }
        foreach ($route['guards'] as $guard) {
            $this->validateGuard($guard);
        }
    }

    private function validateGuard(string $guard): void
    {
        if (!class_exists($guard)) {
            throw new HttpException('Guard "' . $guard . '" does not exist');
        }
    }
}
