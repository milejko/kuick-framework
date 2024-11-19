<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick-framework)
 *
 * @link       https://github.com/milejko/kuick-framework
 * @copyright  Copyright (c) 2010-2024 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\App;

class KernelConfig
{
    public function __construct(private array $values)
    {
    }

    public function get(string $name, ?string $default = null): mixed
    {
        return isset($this->values[$name]) ? $this->values[$name] : $default;
    }

    public function getAll(): array
    {
        return $this->values;
    }
}
