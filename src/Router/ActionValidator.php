<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick-framework)
 *
 * @link       https://github.com/milejko/kuick-framework
 * @copyright  Copyright (c) 2010-2024 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\Router;

use Kuick\Http\HttpException;
use Kuick\Http\RequestMethod;

/**
 *
 */
class ActionValidator
{
    private const MAX_ROUTE_PARAMS = 3;

    public function __invoke(string $routePattern, array $route): void
    {
        $this->validateParameterCount($routePattern, $route);
        $this->validateMethod($routePattern, $route);
        $this->validateAction($routePattern, $route);
        $this->validateGuards($routePattern, $route);
    }

    private function validateParameterCount(string $routePattern, array $route): void
    {
        $parameterCount = self::MAX_ROUTE_PARAMS;
        if (!isset($route['method'])) {
            $parameterCount--;
        }
        if (!isset($route['guards'])) {
            $parameterCount--;
        }
        if (count($route) != $parameterCount) {
            throw new HttpException('Route: ' . $routePattern. ' has invalid parameter count');
        }
    }

    private function validateMethod(string $routePattern, array $route): void
    {
        if (isset($route['method']) && !in_array($route['method'], RequestMethod::ALL_METHODS)) {
            throw new HttpException('Route: ' . $routePattern . ' method invalid');
        }
    }

    private function validateAction(string $routePattern, array $route): void
    {
        if (!isset($route['action'])) {
            throw new HttpException('Route: ' . $routePattern . ' is missing action class name');
        }
        if (!class_exists($route['action'])) {
            throw new HttpException('Action "' . $route['action'] . '" does not exist');
        }
    }

    private function validateGuards(string $routePattern, array $route): void
    {
        if (!isset($route['guards'])) {
            return;
        }
        if (!is_array($route['guards'])) {
            throw new HttpException('Route: ' . $routePattern . ' guards malformed, not an array');
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
