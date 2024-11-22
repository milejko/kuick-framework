<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick-framework)
 *
 * @link       https://github.com/milejko/kuick-framework
 * @copyright  Copyright (c) 2010-2024 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\App;

use Kuick\Http\JsonErrorResponse;
use Kuick\Http\Request;
use Kuick\Router\ActionInvalidMethodException;
use Kuick\Router\ActionLauncher;
use Kuick\Router\ActionMatcher;
use Kuick\Router\ActionNotFoundException;
use Kuick\Router\CommandLauncher;
use Kuick\Router\CommandMatcher;
use Kuick\Security\GuardException;
use Psr\Container\ContainerInterface;
use Throwable;

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
        try {
            ($this->container->get(ApplicationSetLocalization::class))();
            ($this->container->get(ActionLauncher::class))(
                $this->container->get(ActionMatcher::class)->findRoute($request),
                $request
            )->send();
        } catch (GuardException $error) {
            (new JsonErrorResponse($error->getMessage(), $error->getCode()))->send();
        } catch (ActionNotFoundException $error) {
            (new JsonErrorResponse($error->getMessage(), JsonErrorResponse::HTTP_NOT_FOUND))->send();
        } catch (ActionInvalidMethodException $error) {
            (new JsonErrorResponse($error->getMessage(), JsonErrorResponse::HTTP_METHOD_NOT_ALLOWED))->send();
        } catch (Throwable $error) {
            (new JsonErrorResponse($error->getMessage()))->send();
        }
    }

    public function handleConsoleInput(array $argv): void
    {
        try {
            ($this->container->get(ApplicationSetLocalization::class))();
            //@TODO: Command input/output instead of array of strings
            echo $this->container->get(CommandLauncher::class)(
                $this->container->get(CommandMatcher::class)->findRoute($argv),
                $argv
            ) . PHP_EOL;
        } catch (Throwable $error) {
            echo $error->getMessage() . PHP_EOL;
            exit(1);
        }
    }
}
