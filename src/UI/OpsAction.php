<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick-framework)
 *
 * @link       https://github.com/milejko/kuick-framework
 * @copyright  Copyright (c) 2010-2024 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\UI;

use Kuick\App\EnvConfig;
use Kuick\Http\JsonResponse;
use Kuick\Http\Request;

class OpsAction implements ActionInterface
{
    public function __construct(private EnvConfig $envConfig)
    {
        
    }

    public function __invoke(Request $request): JsonResponse
    {
        return new JsonResponse([
            'method' => $request->getMethod(),
            'uri' => $request->getUri(),
            'headers' => $request->getHeaders(),
            'path' => $request->getPath(),
            'pathElements' => $request->getPathElements(),
            'queryParams' => $request->getQueryParams(),
            'body' => $request->getBody(),
            'env' => $this->envConfig->getAll(),
        ]);
    }
}
