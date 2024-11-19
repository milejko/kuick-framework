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
class JsonResponse extends Response
{
    public function __construct(private array $data, private int $code = self::CODE_OK)
    {
        $this->withHeader(HeaderContentType::HEADER_NAME, HeaderContentType::JSON, $this->code);
        $this->withBody(json_encode($this->data));
    }
}
