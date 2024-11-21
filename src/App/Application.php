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

    private const CONFIG_LOCALE_KEY = 'kuick_locale';
    private const CONFIG_CHARSET_KEY = 'kuick_charset';
    private const CONFIG_TIMEZONE_KEY = 'kuick_timezone';

    private const DEFAULT_LOCALE = 'en_US.utf-8';
    private const DEFAULT_TIMEZONE = 'Europe/Warsaw';
    private const DEFAULT_CHARSET = 'UTF-8';

    private const CONTAINER_DEFINITION_LOCATIONS = [
        BASE_PATH . '/etc/di/*.di.php',
        BASE_PATH . '/vendor/kuick/*/etc/di/*.di.php',
    ];
    private const CONTAINER_ENV_SPECIFIC_DEFINITION_LOCATIONS = [
        BASE_PATH . '/etc/di/*.di@%s.php',
    ];

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
        $charset = $appConfig->get(self::CONFIG_CHARSET_KEY, self::DEFAULT_CHARSET);
        $timezone = $appConfig->get(self::CONFIG_TIMEZONE_KEY, self::DEFAULT_TIMEZONE);
        mb_internal_encoding($charset);
        ini_set('default_charset', $charset);
        date_default_timezone_set($timezone);
        ini_set('date.timezone', $timezone);
        setlocale(LC_ALL, $appConfig->get(self::CONFIG_LOCALE_KEY, self::DEFAULT_LOCALE));
        //numbers are always localized to en_US.utf-8'
        setlocale(LC_NUMERIC, self::DEFAULT_LOCALE);
        return $this;
    }
}
