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
use Kuick\UI\ActionInterface;
use Kuick\Security\GuardInterface;
use Psr\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

/**
 *
 */
class ActionLauncher
{
    public function __construct(private ContainerInterface $container)
    {
    }

    public function __invoke(array $route, Request $request): Response
    {
        if (empty($route)) {
            return (new Response())->setStatusCode(Response::HTTP_NO_CONTENT);
        }
        if (isset($route['guards'])) {
            $this->executeGuards($route['guards'], $request);
        }
        $action = $this->container->get($route['action']);
        if (!($action instanceof ActionInterface)) {
            throw new HttpException($route['action'] . ' is not an Action');
        }
        return $action->__invoke($request);
    }

    private function executeGuards(array $guards, Request $request): void
    {
        foreach ($guards as $guardName) {
            $guard = $this->container->get($guardName);
            if (!($guard instanceof GuardInterface)) {
                throw new HttpException($guardName . ' is not a Guard');
            }
            $guard->__invoke($request);
        }
    }
}
