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
use Kuick\Router\ActionMatcher;
use Throwable;

/**
 * Json web application
 */
final class JsonApplication
{
    private const CONTAINER_DEFINITION_LOCATIONS = [
        BASE_PATH . '/etc/di/*.di.php',
        BASE_PATH . '/vendor/kuick-framework/etc/di/*.di.php',
    ];
    private const CONTAINER_ENV_SPECIFIC_DEFINITION_LOCATIONS = [
        BASE_PATH . '/etc/di/*.di.%s.php',
        BASE_PATH . '/vendor/kuick-framework/etc/di/*.di.%s.php',
    ];

    public function __invoke(Request $request): void
    {
        try {
            $container = ContainerFactory::create(
                self::CONTAINER_DEFINITION_LOCATIONS,
                self::CONTAINER_ENV_SPECIFIC_DEFINITION_LOCATIONS
            );
            ($container->get(ActionLauncher::class))(
                $container->get(ActionMatcher::class)->findRoute($request),
                $request
            )->send();
        } catch (Throwable $error) {
            (new JsonErrorResponse($error, $error->getCode()))->send();
        }
    }
}
