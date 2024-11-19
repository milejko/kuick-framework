<?php

/**
 * Kuick
 *
 * @link       https://github.com/milejko/kuick.git
 * @copyright  Copyright (c) 2024 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\Http;

class UnauthorizedException extends HttpException
{
    private const MESSAGE = 'Unauthorized';

    protected $code = Response::CODE_UNAUTHORIZED;
    protected $message = self::MESSAGE;
}
