<?php

/**
 * Kuick
 *
 * @link       https://github.com/milejko/kuick.git
 * @copyright  Copyright (c) 2024 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\Http;

class ForbiddenException extends HttpException
{
    private const MESSAGE = 'Forbidden';

    protected $code = Response::CODE_FORBIDDEN;
    protected $message = self::MESSAGE;
}
