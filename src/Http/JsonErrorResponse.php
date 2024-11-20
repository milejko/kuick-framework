<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick-framework)
 *
 * @link       https://github.com/milejko/kuick-framework
 * @copyright  Copyright (c) 2010-2024 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\Http;

use Throwable;

/**
 *
 */
class JsonErrorResponse extends Response
{
    public function __construct(Throwable $error, private int $code = ResponseCode::INTERNAL_SERVER_ERROR)
    {
        if (!in_array($code, ResponseCode::ALL_CODES)) {
            $this->code = ResponseCode::INTERNAL_SERVER_ERROR;
        }
        $this->withHeader(HeaderContentType::HEADER_NAME, HeaderContentType::JSON, $this->code);
        $this->withBody(json_encode(['error' => $error->getMessage()]));
    }
}
