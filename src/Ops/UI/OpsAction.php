<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick-framework)
 *
 * @link       https://github.com/milejko/kuick-framework
 * @copyright  Copyright (c) 2010-2024 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\Ops\UI;

use Kuick\App\AppConfig;
use Kuick\UI\ActionInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

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
                'headers' => $request->headers->all(),
                'path' => $request->getPathInfo(),
                'queryParams' => $request->query->all(),
                'body' => $request->getContent(),
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
