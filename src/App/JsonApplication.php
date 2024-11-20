<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick-framework)
 *
 * @link       https://github.com/milejko/kuick-framework
 * @copyright  Copyright (c) 2010-2024 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\App;

use Kuick\Router\ActionLauncher;
use Kuick\Router\ActionMatcher;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Throwable;

/**
 * Json web application
 */
final class JsonApplication
{
    private const CONTAINER_DEFINITION_LOCATIONS = [
        BASE_PATH . '/etc/di/*.di.php',
        BASE_PATH . '/vendor/kuick/framework/etc/di/*.di.php',
    ];
    private const CONTAINER_ENV_SPECIFIC_DEFINITION_LOCATIONS = [
        BASE_PATH . '/etc/di/*.di.%s.php',
    ];

    public function __invoke(Request $request): void
    {
        try {
            $container = ContainerFactory::create(
                self::CONTAINER_DEFINITION_LOCATIONS,
                self::CONTAINER_ENV_SPECIFIC_DEFINITION_LOCATIONS
            );

            $defaultCharset = 'UTF-8';
            $defaultLocale = 'en_US.utf-8';
            $defaultTimezone = 'Europe/London';
        
            $config = $container->get(AppConfig::class);
            $charset = $config->get('app_charset', $defaultCharset);
            mb_internal_encoding($charset);
            ini_set('default_charset', $charset);
            date_default_timezone_set($config->get('app_timezone', $defaultTimezone));
            setlocale(LC_ALL, $config->get('app_locale', $defaultLocale));
            setlocale(LC_NUMERIC, $defaultLocale);
    
            ($container->get(ActionLauncher::class))(
                $container->get(ActionMatcher::class)->findRoute($request),
                $request
            )->send();
        } catch (Throwable $error) {
            $errorResponse = new JsonResponse([
                'error' => $error->getMessage(),
                'trace' => getenv('APP_ENV') == 'dev' ? $error->getTrace() : 'not a dev environment',
            ]);
            $errorResponse->setStatusCode($error->getCode() > 0 ? $error->getCode() : JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
            $errorResponse->send();
        }
    }
}
