<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick-framework)
 *
 * @link       https://github.com/milejko/kuick-framework
 * @copyright  Copyright (c) 2010-2024 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\Http;

use Kuick\App\Application;
use Throwable;

class JsonErrorResponse extends JsonResponse
{
    public function __construct(Throwable $error) {
        $responseCode = isset(Response::$statusTexts[$error->getCode()]) ? 
            $error->getCode() : 
            Response::HTTP_INTERNAL_SERVER_ERROR;
        $message = ('' != $error->getMessage()) ? $error->getMessage() : Response::$statusTexts[$responseCode];
        $messageArray = [
            'error' => $message
        ];
        if (Application::getAppEnv() == Application::APP_ENV_PROD) {
            return parent::__construct($messageArray, $responseCode, [], false);
        }
        $messageArray['debugData'] = [
            'exceptionClass' => get_class($this),
            'message' => $message,
            'file' => $error->getFile(),
            'line' => $error->getLine(),
            'trace' => $error->getTrace(),
        ];
        parent::__construct($messageArray, $responseCode, [], false);
    }
}
