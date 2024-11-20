<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick-framework)
 *
 * @link       https://github.com/milejko/kuick-framework
 * @copyright  Copyright (c) 2010-2024 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\App;

class RoutesConfig
{
    public function __construct(private array $values) {}

    public function get(string $name): array
    {
        return $this->values[$name] ?? [];
    }

    public function getAll(): array
    {
        return $this->values;
    }
}
