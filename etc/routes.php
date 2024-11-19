<?php

use Kuick\UI\DefaultRootAction;
use Kuick\Http\HttpMethod;

return [
    'home' => [
        'path' => '/',
        'action' => DefaultRootAction::class,
    ],
    'another' => [
        'method' => HttpMethod::POST, # matching only POST
        'path' => '/test/[a-zA-Z]+', # matching /test/something
        'action' => DefaultRootAction::class,
        'guards' => [] # optional UI guards
    ],
];
