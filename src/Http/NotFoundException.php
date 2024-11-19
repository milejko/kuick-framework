<?php

/**
 * Message Broker
 *
 * @link       https://github.com/milejko/message-broker.git
 * @copyright  Copyright (c) 2024 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\Http;

class NotFoundException extends HttpException
{
    private const MESSAGE = 'Not found';

    protected $code = Response::CODE_NOT_FOUND;
    protected $message = self::MESSAGE;
}
