<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick-framework)
 *
 * @link       https://github.com/milejko/kuick-framework
 * @copyright  Copyright (c) 2010-2024 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\App;

use Kuick\Http\Request;
use Kuick\Router\ActionLauncher;
use Kuick\Router\ActionMatcher;
use Kuick\Router\CommandLauncher;
use Kuick\Router\CommandMatcher;
use Psr\Container\ContainerInterface;

/**
 * Application
 */
final class Application
{
    public const APP_ENV = 'APP_ENV';
    public const ENV_DEV = 'dev';
    public const ENV_PROD = 'prod';

    private ContainerInterface $container;

    public function __construct()
    {
        $lowercaseEnv = strtolower(getenv(self::APP_ENV) ?? self::ENV_PROD);
        $this->container = (new DIContainerBuilder)($lowercaseEnv);
    }

    public function handleRequest(Request $request): void
    {
        //matching and launching UI action
        ($this->container->get(ActionLauncher::class))(
            $this->container->get(ActionMatcher::class)->findRoute($request),
            $request
        )->send();
    }

    public function handleConsoleInput(array $argv): void
    {
        //@TODO: Command input/output instead of array of strings
        echo $this->container->get(CommandLauncher::class)(
            $this->container->get(CommandMatcher::class)->findRoute($argv),
            $argv
        ) . PHP_EOL;
    }
}
