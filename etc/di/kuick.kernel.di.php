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
        //config cache
        $appEnv = getenv('APP_ENV') ?: 'prod';
        $cacheFile = BASE_PATH . '/var/tmp/kuick.config.cache.php';
        if ($appEnv != 'dev') {
            $cacheContent = @file_get_contents($cacheFile);
            if ($cacheContent) {
                return unserialize($cacheContent);
            }
        }
        $configs = [];
        //global config
        foreach (glob(BASE_PATH . '/etc/*.config.php') as $configFile) {
            $configs = array_merge($configs, include $configFile);
        }
        //environment specific config (higher priority)
        foreach (glob(BASE_PATH . '/etc/*.config.' . $appEnv . '.php') as $configFile) {
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
        $appEnv = getenv('APP_ENV') ?: 'prod';
        $cacheFile = BASE_PATH . '/var/tmp/kuick.actionmatcher.cache.php';
        if ($appEnv != 'dev') {
            $cacheContent = @file_get_contents($cacheFile);
            if ($cacheContent) {
                return unserialize($cacheContent);
            }
        }
        $routes = [];
        foreach (glob(BASE_PATH . '/etc/routes/*.actions.php') as $routeFile) {
            $routes = array_merge($routes, include $routeFile);
        }
        $actionMatcher = new ActionMatcher(new RoutesConfig($routes));
        //write cache
        file_put_contents($cacheFile, serialize($actionMatcher));
        return $actionMatcher;
    },

    CommandMatcher::class => function () {
        //config cache
        $appEnv = getenv('APP_ENV') ?: 'prod';
        $cacheFile = BASE_PATH . '/var/tmp/kuick.commandmatcher.cache.php';
        if ($appEnv != 'dev') {
            $cacheContent = @file_get_contents($cacheFile);
            if ($cacheContent) {
                return unserialize($cacheContent);
            }
        }
        $commands = [];
        foreach (glob(BASE_PATH . '/etc/routes/*.commands.php') as $commandFile) {
            $commands = array_merge($commands, include $commandFile);
        }
        $commandMatcher = new CommandMatcher(new RoutesConfig($commands));
        //write cache
        file_put_contents($cacheFile, serialize($commandMatcher));
        return $commandMatcher;
    },
];