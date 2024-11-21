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
use Kuick\Router\ActionLauncher;
use Kuick\Router\ActionMatcher;
use Kuick\Router\CommandLauncher;
use Kuick\Router\CommandMatcher;
use Psr\Container\ContainerInterface;
use Throwable;

/**
 * Json web application
 */
final class Application
{
    public const APP_ENV_DEV = 'dev';
    public const APP_ENV_PROD = 'prod';
    private const APP_ENV_ENV_KEY = 'APP_ENV';

    private const CONTAINER_DEFINITION_LOCATIONS = [
        BASE_PATH . '/etc/di/*.di.php',
        BASE_PATH . '/vendor/kuick/*/etc/di/*.di.php',
    ];
    private const CONTAINER_ENV_SPECIFIC_DEFINITION_LOCATIONS = [
        BASE_PATH . '/etc/di/*.di@%s.php',
    ];
    private const NUMERIC_LOCALE = 'en_US.utf-8';

    private ContainerInterface $container;

    public static function getAppEnv(): string
    {
        return strtolower(getenv(self::APP_ENV_ENV_KEY) ?? self::APP_ENV_PROD);
    }

    public function __construct()
    {
        $this->container = ContainerFactory::create(
            self::CONTAINER_DEFINITION_LOCATIONS,
            self::CONTAINER_ENV_SPECIFIC_DEFINITION_LOCATIONS
        );
        $this->setupLocale();
    }

    public function handleRequest(Request $request): void
    {
        try {
            //matching and launching UI action
            ($this->container->get(ActionLauncher::class))(
                $this->container->get(ActionMatcher::class)->findRoute($request),
                $request
            )->send();
        } catch (Throwable $error) {
            (new JsonErrorResponse($error))->send();
        }
    }

    public function handleConsoleInput(array $argv): void
    {
        try {
            //@TODO: Command input/output instead of array of strings
            echo $this->container->get(CommandLauncher::class)(
                $this->container->get(CommandMatcher::class)->findRoute($argv),
                $argv
            ) . PHP_EOL;
        } catch (Throwable $error) {
            echo $error->getMessage() . PHP_EOL;
            exit($error->getCode());
        }
    }

    private function setupLocale(): self
    {
        $appConfig = $this->container->get(AppConfig::class);
        $charset = $appConfig->get('app_charset');
        mb_internal_encoding($charset);
        ini_set('default_charset', $charset);
        date_default_timezone_set($appConfig->get('app_timezone'));
        setlocale(LC_ALL, $appConfig->get('app_locale'));
        //numbers are always localized
        setlocale(LC_NUMERIC, self::NUMERIC_LOCALE);
        return $this;
    }
}
