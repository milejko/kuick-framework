<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick-framework)
 *
 * @link       https://github.com/milejko/kuick-framework
 * @copyright  Copyright (c) 2010-2024 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\Utils;

/**
 *
 */
class DotEnvParser
{
    public function __invoke(string $fileName): array
    {
        $envValues = [];
        $parsedEnvFile = parse_ini_file($fileName, true);
        foreach ($parsedEnvFile as $envVarKey => $envVarValue) {
            //empty line
            if ('' === $envVarKey) {
                continue;
            }
            $envValues[$this->sanitizeKey($envVarKey)] = $envVarValue;
        }
        return $envValues;
    }

    private function sanitizeKey(string $key): string
    {
        return strtolower(str_replace('_', '.', $key));
    }
}
