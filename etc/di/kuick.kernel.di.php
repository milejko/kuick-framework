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
use Kuick\Router\ActionValidator;
use Kuick\Router\CommandRouteValidator;

/**
 * PHP-DI definitions
 * @see https://php-di.org/doc/php-definitions.html
 */ 
return [    
    AppConfig::class => function () {
        //config cache
        $cacheFile = BASE_PATH . '/var/tmp/kuick.config.cache.' . Application::getAppEnv();
        if (Application::getAppEnv() != Application::APP_ENV_DEV) {
            $cacheContent = @file_get_contents($cacheFile);
            if ($cacheContent) {
                return unserialize($cacheContent);
            }
        }
        $configs = [];
        //vendor config (lowest priority)
        foreach (glob(BASE_PATH . '/vendor/kuick/*/etc/*.config.php') as $configFile) {
            $configs = array_merge($configs, include $configFile);
        }
        //app config (normal priority)
        foreach (glob(BASE_PATH . '/etc/*.config.php') as $configFile) {
            $configs = array_merge($configs, include $configFile);
        }
        //environment specific config (higher priority)
        foreach (glob(BASE_PATH . '/etc/*.config@' . Application::getAppEnv() . '.php') as $configFile) {   
            $configs = array_merge($configs, include $configFile);
        }
        //env config (highest priority)
        $configs = array_merge($configs, getenv());
        $config = new AppConfig($configs);
        //write cache
        file_put_contents($cacheFile, serialize($config));
        return $config;
    },

    ActionMatcher::class => function () {
        //config cache
        $cacheFile = BASE_PATH . '/var/tmp/kuick.actionmatcher.cache.' . Application::getAppEnv();
        if (Application::getAppEnv() != Application::APP_ENV_DEV) {
            $cacheContent = @file_get_contents($cacheFile);
            if ($cacheContent) {
                return unserialize($cacheContent);
            }
        }
        $routes = [];
        //vendor routes (lower priority)
        foreach (glob(BASE_PATH . '/vendor/kuick/*/routes/*.actions.php') as $routeFile) {
            $routes = array_merge($routes, include $routeFile);
        }
        //app config (normal priority)
        foreach (glob(BASE_PATH . '/etc/routes/*.actions.php') as $routeFile) {
            $routes = array_merge($routes, include $routeFile);
        }
        //validating routes
        foreach ($routes as $route) {
            (new ActionValidator())($route);
        }
        $actionMatcher = new ActionMatcher(new RoutesConfig($routes));
        //write cache
        file_put_contents($cacheFile, serialize($actionMatcher));
        return $actionMatcher;
    },

    CommandMatcher::class => function () {
        //config cache
        $cacheFile = BASE_PATH . '/var/tmp/kuick.commandmatcher.cache.' . Application::getAppEnv();
        if (Application::getAppEnv() != Application::APP_ENV_DEV) {
            $cacheContent = @file_get_contents($cacheFile);
            if ($cacheContent) {
                return unserialize($cacheContent);
            }
        }
        $commands = [];
        //vendor commands (lower priority)
        foreach (glob(BASE_PATH . '/vendor/kuick/*/routes/*.actions.php') as $commandFile) {
            $commands = array_merge($commands, include $commandFile);
        }
        //app commands (normal priority)
        foreach (glob(BASE_PATH . '/etc/routes/*.commands.php') as $commandFile) {
            $commands = array_merge($commands, include $commandFile);
        }
        foreach ($commands as $command) {
            (new CommandRouteValidator())($command);
        }
        $commandMatcher = new CommandMatcher(new RoutesConfig($commands));
        //write cache
        file_put_contents($cacheFile, serialize($commandMatcher));
        return $commandMatcher;
    },
];