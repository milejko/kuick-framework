<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick-framework)
 *
 * @link       https://github.com/milejko/kuick-framework
 * @copyright  Copyright (c) 2010-2024 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\Router;

use Kuick\Http\MethodNotAllowedException;
use Kuick\Http\NotFoundException;
use Kuick\Http\Request;
use Psr\Log\LoggerInterface;

/**
 *
 */
class ActionMatcher
{
    private array $routes = [];

    public function __construct(private LoggerInterface $logger)
    {
    }

    public function setRoutes(array $routes): self
    {
        $this->routes = $routes;
        return $this;
    }

    public function getRoutes(): array
    {
        return $this->routes;
    }

    public function findRoute(Request $request): array
    {
        if (Request::METHOD_OPTIONS == $request->getMethod()) {
            return [];
        }
        $requestMethod = $request->getMethod();
        $methodNotAllowedForRoute = null;
        foreach ($this->routes as $route) {
            if (!preg_match('#^' . $route['pattern'] . '$#', $request->getPathInfo())) {
                continue;
            }
            $routeMethod = $route['method'] ?? Request::METHOD_GET;
            if (Request::METHOD_HEAD == $requestMethod && Request::METHOD_GET == $routeMethod) {
                $requestMethod = $routeMethod;
            }
            if ($requestMethod == $routeMethod) {
                $this->logger->debug('Action matched to the pattern: ' . $route['pattern']);
                return $route;
            }
            $this->logger->debug('Method mismatch, but action matched to the pattern: ' . $route['pattern']);
            $methodNotAllowedForRoute = $route;
        }
        if (null !== $methodNotAllowedForRoute) {
            throw new MethodNotAllowedException($requestMethod . ' method is not allowed for ' . $methodNotAllowedForRoute['pattern'] . ' route');
        }
        throw new NotFoundException('Action not found');
    }
}
