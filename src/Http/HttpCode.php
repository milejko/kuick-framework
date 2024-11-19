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
class HttpCode
{
    public const OK = 200;
    public const ACCEPTED = 202;
    public const NO_CONTENT = 204;
    
    public const MOVED_PERMANENTLY = 301;

    public const BAD_REQUEST = 400;
    public const UNAUTHORIZED = 401;
    public const FORBIDDEN = 403;
    public const NOT_FOUND = 404;

    public const INTERNAL_SERVER_ERROR = 500;
    public const BAD_GATEWAY = 502;
    public const SERVICE_UNAVAILABLE = 503;

    public const ALL_CODES = [
        self::OK,
        self::ACCEPTED,
        self::NO_CONTENT,
        self::MOVED_PERMANENTLY,
        self::BAD_REQUEST,
        self::UNAUTHORIZED,
        self::FORBIDDEN,
        self::NOT_FOUND,
        self::INTERNAL_SERVER_ERROR,
        self::SERVICE_UNAVAILABLE,
    ];
}
