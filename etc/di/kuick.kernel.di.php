<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick-framework)
 *
 * @link       https://github.com/milejko/kuick-framework
 * @copyright  Copyright (c) 2010-2024 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace  Kuick\App;

use Kuick\Router\CommandMatcher;
use Kuick\Router\ActionMatcher;

/**
 * PHP-DI definitions
 * @see https://php-di.org/doc/php-definitions.html
 */ 
return [    
    AppConfig::class => function () {
        $configs = [];
        //global config
        foreach (glob(BASE_PATH . '/etc/*.config.php') as $configFile) {
            $configs = array_merge($configs, include $configFile);
        }
        //environment specific config (higher priority)
        $appEnv = getenv('APP_ENV') ?: 'prod';
        foreach (glob(BASE_PATH . '/etc/*.config.' . $appEnv . '.php') as $configFile) {
            $configs = array_merge($configs, include $configFile);
        }
        //env config (highest priority)
        $configs = array_merge($configs, getenv());
        $config = new AppConfig($configs);

        $defaultCharset = 'UTF-8';
        $defaultLocale = 'en_US.utf-8';
        $defaultTimezone = 'Europe/London';
    
        $charset = $config->get('app_charset', $defaultCharset);
        mb_internal_encoding($charset);
        ini_set('default_charset', $charset);
        date_default_timezone_set($config->get('app_timezone', $defaultTimezone));
        setlocale(LC_ALL, $config->get('app_locale', $defaultLocale));
        setlocale(LC_NUMERIC, $defaultLocale);

        return $config;
    },

    ActionMatcher::class => function () {
        $routes = [];
        foreach (glob(BASE_PATH . '/etc/routes/*.actions.php') as $routeFile) {
            $routes = array_merge($routes, include $routeFile);
        }
        return new ActionMatcher(new RoutesConfig($routes));
    },

    CommandMatcher::class => function () {
        $commands = [];
        foreach (glob(BASE_PATH . '/etc/routes/*.commands.php') as $commandFile) {
            $commands = array_merge($commands, include $commandFile);
        }
        return new CommandMatcher(new RoutesConfig($commands));
    },
];