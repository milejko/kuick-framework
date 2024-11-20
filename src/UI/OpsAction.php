<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick-framework)
 *
 * @link       https://github.com/milejko/kuick-framework
 * @copyright  Copyright (c) 2010-2024 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\UI;

use Kuick\App\AppConfig;
use Kuick\Http\JsonResponse;
use Kuick\Http\Request;

class OpsAction implements ActionInterface
{
    public function __construct(private AppConfig $appConfig)
    {
        
    }

    public function __invoke(Request $request): JsonResponse
    {
        return new JsonResponse([
            'request' => [
                'method' => $request->getMethod(),
                'uri' => $request->getUri(),
                'headers' => $request->getHeaders(),
                'path' => $request->getPath(),
                'pathElements' => $request->getPathElements(),
                'queryParams' => $request->getQueryParams(),
                'body' => $request->getBody(),
            ],
            'config' => $this->appConfig->getAll(),
            'server' => [
                'phpversion' => phpversion(),
                'extensions' => implode(', ', get_loaded_extensions()),
                'configuration' => ini_get_all(null, false),
            ]
        ]);
    }
}
