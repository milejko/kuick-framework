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
use Kuick\Http\Response;
use Kuick\Router\ActionLauncher;
use Kuick\Router\ActionMatcher;
use Kuick\Router\CommandLauncher;
use Kuick\Router\CommandMatcher;
use Monolog\Level;
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

    private const EXCEPTION_CODE_LOG_LEVEL_MAP = [
        Response::HTTP_NOT_FOUND => Level::Notice,
        Response::HTTP_UNAUTHORIZED => Level::Notice,
        Response::HTTP_BAD_REQUEST => Level::Warning,
        Response::HTTP_METHOD_NOT_ALLOWED => Level::Warning,
        Response::HTTP_FORBIDDEN => Level::Warning,
    ];

    public function __construct()
    {
        $this->container = (new AppDIContainerBuilder)();
        $this->logger = $this->container->get(LoggerInterface::class);
    }

    public function handleRequest(Request $request): void
    {
        try {
            $this->logger->info('Handling request: ' . $request->getPathInfo());
            //localization setup
            ($this->container->get(AppSetLocalization::class))();
            //matching and launching UI action
            $response = ($this->container->get(ActionLauncher::class))(
                $this->container->get(ActionMatcher::class)->findRoute($request),
                $request
            );
            $response->send();
        } catch (Throwable $error) {
            (new JsonErrorResponse($error->getMessage(), $error->getCode()))->send();
            $this->logger->log(
                self::EXCEPTION_CODE_LOG_LEVEL_MAP[$error->getCode()] ?? Response::HTTP_INTERNAL_SERVER_ERROR,
                $error->getMessage() . ' ' . $error->getFile() . ' (' . $error->getLine() . ') ' . $error->getTraceAsString()
            );
        }
    }

    public function handleConsoleInput(array $argv): void
    {
        try {
            //localization setup
            ($this->container->get(AppSetLocalization::class))();
            //@TODO: Command input/output instead of array of strings
            echo $this->container->get(CommandLauncher::class)(
                $this->container->get(CommandMatcher::class)->findRoute($argv),
                $argv
            ) . PHP_EOL;
            $this->logger->info('Command executed: ' . implode(' ', $argv));
        } catch (Throwable $error) {
            $this->logger->error($error->getMessage());
            echo $error->getMessage() . PHP_EOL;
            exit(1);
        }
    }
}
