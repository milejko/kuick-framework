<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick-framework)
 *
 * @link       https://github.com/milejko/kuick-framework
 * @copyright  Copyright (c) 2010-2024 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

use Kuick\UI\DefaultRootAction;
use Kuick\Http\RequestMethod;
use Kuick\UI\OpsAction;
use Kuick\UI\OpsGuard;

return [
    [
        'path' => '/',
        'action' => DefaultRootAction::class,
    ],
    [
        'method' => RequestMethod::OPTIONS,
        'path' => '/api/ops',
        'action' => OpsAction::class,
        'guards' => [OpsGuard::class]
    ],
];
