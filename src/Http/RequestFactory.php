<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick-framework)
 *
 * @link       https://github.com/milejko/kuick-framework
 * @copyright  Copyright (c) 2010-2024 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\Http;

/**
 *
 */
class RequestFactory
{
    private const REQUEST_METHOD = 'REQUEST_METHOD';
    private const SERVER_HEADER_PREFIX = 'HTTP_';

    private static array $serverVars;

    public static function create(array $serverVars, string $body = ''): Request
    {
        self::$serverVars = $serverVars;
        $request = new Request();
        $request->withMethod(self::getServerVariable(self::REQUEST_METHOD, RequestMethod::GET));
        $request->withUri(
            strpos(self::getServerVariable('SERVER_PROTOCOL'), 'HTTPS') ? 'https://' : 'http://' .
            self::getServerVariable('HTTP_HOST') . self::getServerVariable('REQUEST_URI')
        );
        $request->withBody($body);
        //headers
        foreach ($serverVars as $name => $value) {
            if (!str_starts_with($name, self::SERVER_HEADER_PREFIX)) {
                continue;
            }
            $request->withHeader(str_replace('_', '-', substr($name, strlen(self::SERVER_HEADER_PREFIX))), $value);
        }
        return $request;
    }

    private static function getServerVariable(string $key, string $default = ''): mixed
    {
        return isset(self::$serverVars[$key]) ? self::$serverVars[$key]: $default;
    }
}
