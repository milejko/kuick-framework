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

class JsonErrorResponse extends JsonErrorResponse
{
    public function __construct(Throwable $exception, int $code = Response::HTTP_INTERNAL_SERVER_ERROR)
    {
        parent::__construct($exception->getMessage(), $code);
    }
}
