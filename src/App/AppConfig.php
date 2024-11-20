<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick-framework)
 *
 * @link       https://github.com/milejko/kuick-framework
 * @copyright  Copyright (c) 2010-2024 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\App;

class AppConfig
{
    public function __construct(private array $values)
    {
        $lowercaseKeyValues = [];
        foreach ($values as $name => $value) {
            $lowercaseKeyValues[strtolower($name)] = $value;
        }
        $this->values = $lowercaseKeyValues;
    }

    public function get(string $name, mixed $defaultValue = null): mixed
    {
        $lowercaseName = strtolower($name);
        return $this->values[$lowercaseName] ?? $defaultValue;
    }

    public function getAll(): array
    {
        return $this->values;
    }
}
