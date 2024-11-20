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
use Symfony\Component\HttpFoundation\Request;

/**
 *
 */
class ActionValidator
{
    private const MAX_ROUTE_PARAMS = 4;

    public function __invoke(array $route): void
    {
        $this->validatePattern($route);
        $this->validateParameterCount($route);
        $this->validateMethod($route);
        $this->validateAction($route);
        $this->validateGuards($route);
    }

    private function validatePattern(array $route): void
    {
        if (!isset($route['pattern'])) {
            throw new HttpException('One or more actions are missing pattern');
        }
        if (!is_string($route['pattern'])) {
            throw new HttpException('One or more actions pattern is invalid');
        }
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
            throw new HttpException('Action: ' . $route['pattern'] . ' has invalid parameter count');
        }
    }

    private function validateMethod(array $route): void
    {
        if (
            isset($route['method']) && !in_array(
                $route['method'],
                [
                    Request::METHOD_GET,
                    Request::METHOD_HEAD,
                    Request::METHOD_OPTIONS,
                    Request::METHOD_POST,
                    Request::METHOD_PUT,
                    Request::METHOD_DELETE,
                    Request::METHOD_PATCH,
                ]
            )
        ) {
            throw new HttpException('Action: ' . $route['pattern'] . ' method invalid');
        }
    }

    private function validateAction(array $route): void
    {
        if (!isset($route['action'])) {
            throw new HttpException('Action: ' . $route['pattern'] . ' is missing action class name');
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
            throw new HttpException('Action: ' . $route['pattern'] . ' guards malformed, not an array');
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
