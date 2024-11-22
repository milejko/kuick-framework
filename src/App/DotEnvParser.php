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
class DotEnvParser
{
    private const OPEN_FILE_MODE = 'r';

    public function __invoke(string $fileName): array
    {
        if (!file_exists($fileName)) {
            throw new ApplicationException('DotEnv file not found');
        }
        $fileResource = fopen($fileName, self::OPEN_FILE_MODE);
        $values = [];
        while (!feof($fileResource)) {
            $kv = $this->parseLine(fgets($fileResource));
            //empty line
            if ('' === (current($kv) ?? '')) {
                continue;
            }
            $values[$this->sanitizeKey(current($kv))] = next($kv) ?? '';
        }
        fclose($fileResource);
        return $values;
    }

    private function sanitizeKey(string $key): string
    {
        return strtolower(str_replace('_', '.', $key));
    }

    private function parseLine(string $line): array
    {
        return explode('=', trim($line));
    }
}
