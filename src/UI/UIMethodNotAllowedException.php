<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick-framework)
 *
 * @link       https://github.com/milejko/kuick-framework
 * @copyright  Copyright (c) 2010-2024 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\UI;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class UIMethodNotAllowedException extends Exception
{
    protected $code = Response::HTTP_METHOD_NOT_ALLOWED;
    protected $message = 'Method not allowed';
}
