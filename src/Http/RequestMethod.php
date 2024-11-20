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
class RequestMethod
{
    public const GET = 'GET';
    public const POST = 'POST';
    public const PUT = 'PUT';
    public const PATCH = 'PATCH';
    public const HEAD = 'HEAD';
    public const DELETE = 'DELETE';
    public const OPTIONS = 'OPTIONS';

    public const ALL_METHODS = [
        self::GET,
        self::POST,
        self::DELETE,
        self::HEAD,
        self::PUT,
        self::PATCH,
        self::OPTIONS,
    ];
}
