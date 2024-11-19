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
class JsonResponse extends Response
{
    public function __construct(private array $data, private int $code = self::CODE_OK)
    {
        $this->withHeader(ContentType::HEADER_NAME, ContentType::JSON, $this->code);
        $this->withBody(json_encode($this->data));
    }
}
