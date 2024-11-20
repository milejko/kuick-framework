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
use Kuick\Http\Request;
use Kuick\Http\Response;
use Kuick\Http\ResponseCode;
use Kuick\UI\ActionInterface;
use Kuick\Security\GuardInterface;
use Psr\Container\ContainerInterface;

/**
 *
 */
class ActionLauncher
{
    private const EMPTY_OPTIONS_HEADER = 'X-Options-Request';

    public function __construct(private ContainerInterface $container)
    {
    }

    public function __invoke(array $route, Request $request): Response
    {
        if (empty($route)) {
            return (new Response)->withHeader(self::EMPTY_OPTIONS_HEADER, ResponseCode::NO_CONTENT, ResponseCode::NO_CONTENT);
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
