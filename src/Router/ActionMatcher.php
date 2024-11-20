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
use Kuick\UI\UIMethodNotAllowedException;
use Kuick\UI\UINotFoundException;
use Symfony\Component\HttpFoundation\Request;

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
        if (Request::METHOD_OPTIONS == $request->getMethod()) {
            return [];
        }
        $methodNotAllowed = false;
        foreach ($this->routes->getAll() as $route) {
            (new ActionValidator())($route);
            $routeMethod = $route['method'] ?? Request::METHOD_GET;            
            if (!preg_match('#^' . $route['pattern'] . '$#', $request->getPathInfo())) {
                continue;
            }
            if (Request::METHOD_HEAD == $request->getMethod() && Request::METHOD_GET == $routeMethod) {
                $routeMethod = Request::METHOD_GET;
            }
            if ($request->getMethod() == $routeMethod) {
                return $route;
            }
            $methodNotAllowed = true;
        }
        if ($methodNotAllowed) {
            throw new UIMethodNotAllowedException();
        }
        throw new UINotFoundException();
    }
}
