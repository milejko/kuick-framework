<?php

/**
 * Kuick
 *
 * @link       https://github.com/milejko/kuick.git
 * @copyright  Copyright (c) 2024 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\Http;

use Exception;

class HttpException extends Exception
{
    private const MESSAGE = 'Internal server error';

    protected $code = Response::CODE_ERROR;
    protected $message = self::MESSAGE;
}
