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
use Kuick\Http\Request;
use Psr\Log\LoggerInterface;

/**
 *
 */
class ActionMatcher
{
    private RoutesConfig $routes;

    public function __construct(private LoggerInterface $logger) {}

    public function setRoutes(RoutesConfig $routes): self
    {
        $this->routes = $routes;
        return $this;
    }

    public function getRoutes(): array
    {
        return $this->routes->getAll();
    }

    public function findRoute(Request $request): array
    {
        if (Request::METHOD_OPTIONS == $request->getMethod()) {
            return [];
        }
        $methodNotAllowed = false;
        foreach ($this->routes->getAll() as $route) {
            $routeMethod = $route['method'] ?? Request::METHOD_GET;
            $requestMethod = $request->getMethod();
            if (!preg_match('#^' . $route['pattern'] . '$#', $request->getPathInfo())) {
                continue;
            }
            if (Request::METHOD_HEAD == $requestMethod && Request::METHOD_GET == $routeMethod) {
                $requestMethod = $routeMethod;
            }
            if ($requestMethod == $routeMethod) {
                return $route;
            }
            $methodNotAllowed = true;
        }
        if ($methodNotAllowed) {
            throw new ActionInvalidMethodException();
        }
        throw new ActionNotFoundException();
    }
}
