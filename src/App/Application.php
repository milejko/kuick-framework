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
use Kuick\Http\ResponseException;
use Kuick\Router\ActionInvalidMethodException;
use Kuick\Router\ActionLauncher;
use Kuick\Router\ActionMatcher;
use Kuick\Router\ActionNotFoundException;
use Kuick\Router\CommandLauncher;
use Kuick\Router\CommandMatcher;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Throwable;

/**
 * Application
 */
final class Application
{
    public const APP_ENV = 'KUICK_APP_ENV';
    public const ENV_DEV = 'dev';
    public const ENV_PROD = 'prod';

    private ContainerInterface $container;
    private LoggerInterface $logger;

    public function __construct()
    {
        $this->container = (new AppDIContainerBuilder)();
        $this->logger = $this->container->get(LoggerInterface::class);
    }

    public function handleRequest(Request $request): void
    {
        //matching and launching UI action
        try {
            $this->logger->info($request->getPathInfo() . ' - Start processing request');
            ($this->container->get(AppSetLocalization::class))();
            $response = ($this->container->get(ActionLauncher::class))(
                $this->container->get(ActionMatcher::class)->findRoute($request),
                $request
            );
            $response->send();
        } catch (ResponseException $error) {
            (new JsonErrorResponse($error->getMessage(), $error->getCode()))->send();
            $this->logger->notice($request->getPathInfo() . ' - ' . $error->getMessage());
        } catch (ActionNotFoundException $error) {
            $this->logger->notice($request->getPathInfo() . ' - ' . $error->getMessage());
            (new JsonErrorResponse($error->getMessage(), JsonErrorResponse::HTTP_NOT_FOUND))->send();
            $this->logger->notice($request->getPathInfo() . ' - ' . $error->getMessage());
        } catch (ActionInvalidMethodException $error) {
            (new JsonErrorResponse($error->getMessage(), JsonErrorResponse::HTTP_METHOD_NOT_ALLOWED))->send();
            $this->logger->notice($request->getPathInfo() . ' - ' . $error->getMessage());
        } catch (Throwable $error) {
            (new JsonErrorResponse($error->getMessage()))->send();
            $this->logger->error($request->getPathInfo() . ' - ' . $error->getMessage());
        }
        $this->logger->info($request->getPathInfo() . ' - Response sent');
    }

    public function handleConsoleInput(array $argv): void
    {
        try {
            ($this->container->get(AppSetLocalization::class))();
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
