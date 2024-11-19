<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick-framework)
 *
 * @link       https://github.com/milejko/kuick-framework
 * @copyright  Copyright (c) 2010-2024 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace  Kuick\App;

use Kuick\Console\CommandLauncher;
use Kuick\Console\CommandMatcher;
use Kuick\Router\ActionLauncher;
use Kuick\Router\RouteMatcher;

use function DI\autowire;

return [
    
    EnvConfig::class => function () {
        return new EnvConfig(getenv());
    },
    
    KernelConfig::class => function () {
        $config = new KernelConfig(include BASE_PATH . '/etc/config.php');

        $defaultCharset = 'UTF-8';
        $defaultLocale = 'en_US.utf-8';
        $defaultTimezone = 'Europe/London';
    
        $charset = $config->get('charset', $defaultCharset);
        mb_internal_encoding($charset);
        ini_set('default_charset', $charset);
        date_default_timezone_set($config->get('timezone', $defaultTimezone));
        setlocale(LC_ALL, $config->get('locale', $defaultLocale));
        setlocale(LC_NUMERIC, $defaultLocale);

        return $config;
    },

    RouteMatcher::class => function () {
        $routes = (new KernelConfig(include BASE_PATH . '/etc/routes.php'))->getAll();
        return new RouteMatcher($routes);
    },

    CommandMatcher::class => function () {
        $commands = (new KernelConfig(include BASE_PATH . '/etc/commands.php'))->getAll();
        return new CommandMatcher($commands);
    },

    ActionLauncher::class => autowire(ActionLauncher::class),

    CommandLauncher::class => autowire(CommandLauncher::class),

];