<?php

/**
 * Message Broker
 *
 * @link       https://github.com/milejko/message-broker.git
 * @copyright  Copyright (c) 2024 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\Http;

/**
 *
 */
class JsonRequest extends Request
{
    public function __construct()
    {
        $this->withHeader(ContentType::HEADER_NAME, ContentType::JSON);
    }
}
