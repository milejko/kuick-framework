<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick-framework)
 *
 * @link       https://github.com/milejko/kuick-framework
 * @copyright  Copyright (c) 2010-2024 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\Router;

use Kuick\App\RoutesConfig;
use Kuick\Http\HttpNotFoundException;
use Kuick\Http\Request;
use Kuick\Http\RequestMethod;

/**
 *
 */
class ActionMatcher
{
    public function __construct(private RoutesConfig $routes)
    {
    }

    public function getRoutes(): array
    {
        return $this->routes->getAll();
    }

    public function findRoute(Request $request): array
    {
        foreach ($this->routes->getAll() as $routePattern => $route) {
            (new ActionValidator)($routePattern, $route);
            $routeMethod = $route['method'] ?? RequestMethod::GET;
            if ($request->getMethod() != $routeMethod) {
                continue;
            }
            if (preg_match('#^' . $routePattern . '$#', $request->getPath())) {
                return $route;
            }
        }
        throw new HttpNotFoundException('Route not found');
    }
}
