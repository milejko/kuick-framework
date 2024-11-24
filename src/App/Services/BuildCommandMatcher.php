<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick-framework)
 *
 * @link       https://github.com/milejko/kuick-framework
 * @copyright  Copyright (c) 2010-2024 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\App\Services;

use Kuick\Router\CommandMatcher;
use Kuick\Router\CommandRouteValidator;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

/**
 *
 */
class BuildCommandMatcher extends ServiceBuildAbstract
{
    public function __invoke(): void
    {
        $this->builder->addDefinitions([CommandMatcher::class => function (ContainerInterface $container): CommandMatcher {
            $routes = [];
            //app commands (normal priority)
            foreach (glob(BASE_PATH . '/etc/routes/*.commands.php') as $commandFile) {
                $routes = array_merge($routes, include $commandFile);
            }
            foreach ($routes as $route) {
                (new CommandRouteValidator())($route);
            }
            $commandMatcher = (new CommandMatcher($container->get(LoggerInterface::class)))->setRoutes($routes);
            return $commandMatcher;
        }]);
    }
}
