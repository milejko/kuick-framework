<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick-framework)
 *
 * @link       https://github.com/milejko/kuick-framework
 * @copyright  Copyright (c) 2010-2024 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\Ops\UI;

use DI\Container;
use Kuick\Http\JsonResponse;
use Kuick\Http\Request;
use Kuick\UI\ActionInterface;

class OpsAction implements ActionInterface
{
    public function __construct(private Container $container)
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
            'config' => $this->getConfigDefinitions(),
            'server' => [
                'phpversion' => phpversion(),
                'peakMemory' => round(memory_get_peak_usage() / 1024 / 1024, 2) . ' MB',
                'extensions' => implode(', ', get_loaded_extensions()),
                'configuration' => ini_get_all(null, false),
            ]
        ]);
    }

    private function getConfigDefinitions(): array
    {
        $configValues = [];
        foreach ($this->container->getKnownEntryNames() as $entryName) {
            $definition = $this->container->get($entryName);
            if (true === $definition) {
                continue;
            }
            if (!is_string($definition) && !is_array($definition)) {
                continue;
            }
            $configValues[$entryName] = $definition;
        }
        return $configValues;
    }
}
