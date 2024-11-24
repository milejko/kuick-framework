<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick-framework)
 *
 * @link       https://github.com/milejko/kuick-framework
 * @copyright  Copyright (c) 2010-2024 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\App;

/**
 *
 */
class AppGetEnvironment
{
    public function __invoke(): array
    {
        $dotEnvFilePath = BASE_PATH . '/.env';
        $dotEnvLocalFilePath = BASE_PATH . '/.env.local';
        $dotEnvVars = array_merge(
            file_exists($dotEnvFilePath) ? parse_ini_file($dotEnvFilePath, true) : [],
            file_exists($dotEnvLocalFilePath) ? parse_ini_file($dotEnvLocalFilePath, true) : [],
        );
        $envVars = [];
        foreach (array_merge($dotEnvVars, getenv()) as $envVarKey => $envVarValue) {
            $envVars[$this->sanitizeKey($envVarKey)] = $envVarValue;
        }
        return $envVars;
    }

    private function sanitizeKey(string $key): string
    {
        return str_replace('_', '.', strtolower($key));
    }
}
