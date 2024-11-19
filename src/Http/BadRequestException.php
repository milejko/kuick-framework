<?php

/**
 * Kuick
 *
 * @link       https://github.com/milejko/kuick.git
 * @copyright  Copyright (c) 2024 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\Http;

class BadRequestException extends HttpException
{
    private const MESSAGE = 'Bad request';

    protected $code = Response::CODE_BAD_REQUEST;
    protected $message = self::MESSAGE;
}
