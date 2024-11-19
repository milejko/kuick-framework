<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick-framework)
 *
 * @link       https://github.com/milejko/kuick-framework
 * @copyright  Copyright (c) 2010-2024 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\Router;

use Kuick\Http\HttpMethod;
use Kuick\Http\NotFoundException;
use Kuick\Http\Request;

/**
 *
 */
class RouteMatcher
{
    public function __construct(private array $routes = [])
    {
    }

    public function matchRoute(Request $request): array
    {
        foreach ($this->routes as $route) {
            $routeMethod = isset($route['method']) ? $route['method'] : HttpMethod::GET;
            if ($request->getMethod() != $routeMethod) {
                continue;
            }
            if (preg_match('#^' . $route['path'] . '$#', $request->getPath())) {
                return $route;
            }
        }
        throw new NotFoundException('Route not found');
    }
}
