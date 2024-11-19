<?php

use Kuick\Guards\OpsGuard;
use Kuick\UI\DefaultRootAction;
use Kuick\Http\RequestMethod;
use Kuick\UI\OpsAction;

return [
    'home' => [
        'path' => '/',
        'action' => DefaultRootAction::class,
    ],
    'operations' => [
        'method' => RequestMethod::OPTIONS,
        'path' => '/api/ops',
        'action' => OpsAction::class,
        'guards' => [OpsGuard::class]
    ],
];
