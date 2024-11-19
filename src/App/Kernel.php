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
use Kuick\Router\ActionLauncher;
use Kuick\Router\RouteMatcher;
use Kuick\Router\RouteValidator;
use Throwable;

/**
 *
 */
class Kernel
{
    private const CONFIG_PATH = BASE_PATH . '/etc/config.php';
    private const ROUTES_PATH = BASE_PATH . '/etc/routes.php';
    
    private const DEFAULT_CHARSET = 'UTF-8';
    private const DEFAULT_LOCALE = 'en_US.UTF-8';

    private readonly KernelConfig $config;
    private readonly KernelConfig $routes;
    private readonly KernelConfig $env;

    private readonly RouteMatcher $routeMatcher;
    private readonly ActionLauncher $actionLauncher;

    public function __construct(private readonly Request $request)
    {
        //@TODO: add configuration to the container
        $this->config = new KernelConfig(include self::CONFIG_PATH);
        $this->routes = new KernelConfig(include self::ROUTES_PATH);
        $this->env = new KernelConfig(getenv());

        $charset = $this->config->get('charset', self::DEFAULT_CHARSET);
        mb_internal_encoding($charset);
        ini_set('default_charset', $charset);

        setlocale(LC_ALL, $this->config->get('locale', self::DEFAULT_LOCALE));
        setlocale(LC_NUMERIC, self::DEFAULT_LOCALE);

        foreach ($this->routes->getAll() as $route) {
            (new RouteValidator)($route);
        }

        //@TODO: configure container and get matcher and launcher from there
        $this->routeMatcher = new RouteMatcher($this->routes->getAll());
        $this->actionLauncher = new ActionLauncher();
        return $this;
    }

    public function run(): void
    {
        try {
            ($this->actionLauncher)(
                $this->routeMatcher->matchRoute($this->request),
                $this->request
            )->send();
        } catch (Throwable $error) {
            (new JsonErrorResponse($error, $error->getCode()))->send();
        }
    }
}
