<?php

/**
 * Kuick
 *
 * @link       https://github.com/milejko/kuick.git
 * @copyright  Copyright (c) 2024 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\UI;

use Kuick\Http\Request;

/**
 *
 */
interface GuardInterface
{
    public function __invoke(Request $request): void;
}
