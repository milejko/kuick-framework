<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick-framework)
 *
 * @link       https://github.com/milejko/kuick-framework
 * @copyright  Copyright (c) 2010-2024 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\App\Services;

use Kuick\Router\ActionMatcher;
use Kuick\Router\ActionValidator;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

/**
 *
 */
class BuildActionMatcher extends ServiceBuildAbstract
{
    public function __invoke(): void
    {
        $this->builder->addDefinitions([ActionMatcher::class => function (ContainerInterface $container): ActionMatcher {
            $routes = [];
            //app config (normal priority)
            foreach (glob(BASE_PATH . '/etc/routes/*.actions.php') as $routeFile) {
                $routes = array_merge($routes, include $routeFile);
            }
            //validating routes
            foreach ($routes as $route) {
                (new ActionValidator())($route);
            }
            $actionMatcher = (new ActionMatcher($container->get(LoggerInterface::class)))->setRoutes($routes);
            return $actionMatcher;
        }]);
    }
}
