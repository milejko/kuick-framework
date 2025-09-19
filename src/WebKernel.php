<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick-framework)
 *
 * @link       https://github.com/milejko/kuick-framework
 * @copyright  Copyright (c) 2010-2025 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://github.com/milejko/kuick-framework?tab=MIT-1-ov-file#readme New BSD License
 */

namespace Kuick\Framework;

use Kuick\Framework\Config\ConfigIndexer;
use Kuick\Framework\Events\KernelCreatedEvent;
use Kuick\Routing\Router;
use Kuick\Security\Guardhouse;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;

/**
 * Web application Kernel
 */
final class WebKernel extends KernelAbstract
{
    public function __construct(string $projectDir)
    {
        parent::__construct($projectDir);
        $logger = $this->getContainer()->get(LoggerInterface::class);
        $configIndexer = $this->getContainer()->get(ConfigIndexer::class);

        // adding guards to Guardhouse
        $guardhouse = $this->getContainer()->get(Guardhouse::class);
        foreach ($configIndexer->getConfigFilePaths(ConfigIndexer::GUARDS_FILE_SUFFIX) as $guardConfigFile) {
            foreach (require $guardConfigFile as $guardConfig) {
                $logger->debug("Adding guard: $guardConfig->path");
                $guardhouse->addGuard(
                    $guardConfig->path,
                    $this->getContainer()->get($guardConfig->guardClassName),
                    $guardConfig->methods
                );
            }
        }
        $logger->info('Guardhouse initialized');

        // adding routes to Router
        $router = $this->getContainer()->get(Router::class);
        foreach ($configIndexer->getConfigFilePaths(ConfigIndexer::ROUTES_FILE_SUFFIX) as $routeConfigFile) {
            foreach (require $routeConfigFile as $routeConfig) {
                $logger->debug("Adding route: $routeConfig->path", $routeConfig->methods);
                $router->addRoute(
                    $routeConfig->path,
                    $this->getContainer()->get($routeConfig->controllerClassName),
                    $routeConfig->methods
                );
            }
        }
        $logger->info('Router initialized');

        // adding middlewares to StackRequestHandler
        $requestHandler = $this->getContainer()->get(RequestHandlerInterface::class);
        foreach ($configIndexer->getConfigFilePaths(ConfigIndexer::MIDDLEWARES_FILE_SUFFIX) as $middlewareConfigFile) {
            foreach (require $middlewareConfigFile as $middlewareConfig) {
                $logger->debug("Adding middleware: $middlewareConfig->middlewareClassName", $middlewareConfig->beforeMiddlewareClassName ? ['before' => $middlewareConfig->beforeMiddlewareClassName] : []);
                $requestHandler->addMiddleware(
                    $this->getContainer()->get($middlewareConfig->middlewareClassName),
                    $middlewareConfig->beforeMiddlewareClassName
                );
            }
        }
        $logger->info('Request Handler initialized');

        // dispatching KernelCreatedEvent
        $this->getContainer()->get(EventDispatcherInterface::class)->dispatch(new KernelCreatedEvent($this));
    }
}
